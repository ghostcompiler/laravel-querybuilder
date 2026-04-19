<?php

namespace GhostCompiler\LaravelQueryBuilder\Support;

use Carbon\CarbonImmutable;
use Closure;
use GhostCompiler\LaravelQueryBuilder\Exceptions\InvalidQueryBuilderQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use Throwable;
use WeakMap;

class QueryBuilderEngine
{
    /**
     * @var list<string>
     */
    protected array $allowedRequestKeys = [
        'search',
        'filters',
        'sort_by',
        'sort_dir',
        'page',
        'per_page',
        'date_from',
        'date_to',
        'date_column',
        'columns',
        'with',
        'trashed',
    ];

    /**
     * @var WeakMap<object, list<string>>|null
     */
    protected static ?WeakMap $appliedSortRegistry = null;

    /**
     * @var WeakMap<object, array<string, mixed>>|null
     */
    protected static ?WeakMap $requestRegistry = null;

    /**
     * @var WeakMap<object, array<string, mixed>>|null
     */
    protected static ?WeakMap $responseRegistry = null;

    /**
     * @var array<string, array<int, string>>
     */
    protected static array $columnCache = [];

    public function apply(Builder $query, Request|array $request, array $response = []): Builder
    {
        $errors = [];
        $params = $this->validatedRequestParams($request, $errors);
        $response = $this->validatedResponseOptions($response, $errors);

        $query = $this->applyColumnSelection($query, $params, $errors);
        $query = $this->applySoftDeletes($query, $params, $errors);
        $query = $this->applySearch($query, $params, $errors);
        $query = $this->applyFilters($query, $params, $errors);
        $query = $this->applyDateRange($query, $params, $errors);
        $query = $this->applyEagerLoads($query, $params, $errors);
        $query = $this->applySorting($query, $params, $errors);

        $this->rememberRequestParams($query, $params);
        $this->rememberResponseOptions($query, $response);
        $this->throwIfStrict($query->getModel(), $errors);

        return $query;
    }

    public function paginate(Builder $query, Request|array|null $request = null): LengthAwarePaginator
    {
        $params = $this->resolveRequestParams($query, $request);
        $page = max((int) ($params['page'] ?? 1), 1);

        return $query->paginate(
            $this->resolvePerPage($query->getModel(), $params),
            ['*'],
            'page',
            $page,
        );
    }

    public function paginateTable(Builder $query, Request|array|null $request = null, array $response = []): array
    {
        $params = $this->resolveRequestParams($query, $request);
        $responseOptions = $this->resolveResponseOptions($query, $response);
        $paginator = $this->paginate($query, $params);
        $appliedSorts = $this->appliedSortsFor($query)
            ?? $this->summarizeRequestedSorts($query->getModel(), $params);

        $payload = [
            $responseOptions['status_key'] => $responseOptions['status'],
            'data' => $paginator->items(),
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more' => $paginator->hasMorePages(),
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
            ],
            'meta' => [
                'search' => $params['search'] ?? null,
                'applied_sorts' => $appliedSorts,
                'applied_filters' => $this->summarizeFilters($params),
            ],
        ];

        if (is_string($responseOptions['message']) && trim($responseOptions['message']) !== '') {
            $payload[$responseOptions['message_key']] = $responseOptions['message'];
        }

        return $payload;
    }

    protected function applyColumnSelection(Builder $query, array $params, array &$errors): Builder
    {
        $columns = $params['columns'] ?? null;

        if ($columns === null || $columns === '') {
            return $query;
        }

        $requested = $this->stringOrArrayToArray($columns);
        $model = $query->getModel();
        $selectable = $this->modelArrayOption($model, 'selectable');
        $tableColumns = $this->tableColumns($model);

        if ($requested === [] || $tableColumns === []) {
            return $query;
        }

        if ($selectable === []) {
            $this->recordError($errors, 'columns', 'Column selection requires an explicit [$selectable] allow-list on the model.');

            return $query;
        }

        $safeColumns = array_values(array_intersect($requested, $tableColumns, $selectable));
        $keyName = $model->getKeyName();

        if ($safeColumns === []) {
            $this->recordError($errors, 'columns', 'No requested columns are allowed on this model.');

            return $query;
        }

        $invalidColumns = array_values(array_diff($requested, $safeColumns));

        foreach ($invalidColumns as $invalidColumn) {
            $this->recordError($errors, 'columns', "Column [{$invalidColumn}] is not selectable.");
        }

        if (! in_array($keyName, $safeColumns, true)) {
            $safeColumns[] = $keyName;
        }

        $query->select(array_map(
            fn (string $column) => $this->qualify($model, $column),
            $safeColumns
        ));

        return $query;
    }

    protected function applySoftDeletes(Builder $query, array $params, array &$errors): Builder
    {
        $model = $query->getModel();

        if (! $this->usesSoftDeletes($model)) {
            if (($params['trashed'] ?? null) !== null) {
                $this->recordError($errors, 'trashed', 'The model does not use soft deletes.');
            }

            return $query;
        }

        $trashed = $params['trashed'] ?? null;

        if ($trashed !== null && ! in_array($trashed, ['with', 'only'], true)) {
            $this->recordError($errors, 'trashed', 'The trashed parameter must be either [with] or [only].');
        }

        if ($trashed === 'with') {
            return $query->withoutGlobalScope(SoftDeletingScope::class);
        }

        if ($trashed === 'only') {
            $deletedAtColumn = method_exists($model, 'getDeletedAtColumn')
                ? $model->getDeletedAtColumn()
                : 'deleted_at';

            return $query
                ->withoutGlobalScope(SoftDeletingScope::class)
                ->whereNotNull($model->qualifyColumn($deletedAtColumn));
        }

        return $query;
    }

    protected function applySearch(Builder $query, array $params, array &$errors): Builder
    {
        $term = trim((string) ($params['search'] ?? ''));
        $searchable = $this->modelArrayOption($query->getModel(), 'searchable');

        if ($term === '' || $searchable === []) {
            return $query;
        }

        if (mb_strlen($term) < $this->minSearchLength()) {
            $this->recordError($errors, 'search', 'The search term is shorter than the configured minimum length.');

            return $query;
        }

        $model = $query->getModel();
        $tableColumns = $this->tableColumns($model);

        $query->where(function (Builder $nested) use ($searchable, $term, $model, $tableColumns, &$errors): void {
            foreach ($searchable as $field) {
                if (! is_string($field) || $field === '') {
                    continue;
                }

                if (str_contains($field, '.')) {
                    if (! $this->isAllowedRelationDepth($field)) {
                        $this->recordError($errors, 'search', "Search field [{$field}] exceeds the configured maximum relation depth.");

                        continue;
                    }

                    $leafColumn = $this->leafColumn($field);

                    $this->whereHasNested(
                        $nested,
                        $field,
                        fn (Builder $relationQuery) => $relationQuery->where(
                            $leafColumn,
                            'LIKE',
                            $this->likePattern($term, $this->searchLikeMode())
                        ),
                        true,
                    );

                    continue;
                }

                if ($tableColumns !== [] && ! in_array($field, $tableColumns, true)) {
                    $this->recordError($errors, 'search', "Search field [{$field}] is not a valid column.");

                    continue;
                }

                $nested->orWhere(
                    $this->qualify($model, $field),
                    'LIKE',
                    $this->likePattern($term, $this->searchLikeMode())
                );
            }
        });

        return $query;
    }

    protected function applyFilters(Builder $query, array $params, array &$errors): Builder
    {
        $filters = $params['filters'] ?? [];

        if (! is_array($filters) || $filters === []) {
            return $query;
        }

        $filters = array_slice($filters, 0, $this->maxFilterCount(), true);
        $model = $query->getModel();
        $filterable = $this->modelArrayOption($model, 'filterable');
        $customFilters = $this->customFilters($model);
        $tableColumns = $this->tableColumns($model);

        if ($filterable === [] && $customFilters === []) {
            $this->recordError($errors, 'filters', 'Filtering requires an explicit [$filterable] or [$customFilters] allow-list on the model.');

            return $query;
        }

        foreach ($filters as $field => $definition) {
            if (! is_string($field) || $field === '') {
                continue;
            }

            if ($this->isProtectedSoftDeleteFilter($model, $field)) {
                $this->recordError($errors, "filters.{$field}", "Filtering by [{$field}] is reserved for the [trashed] query-builder command.");

                continue;
            }

            $hasCustomFilter = array_key_exists($field, $customFilters);

            if (! in_array($field, $filterable, true) && ! $hasCustomFilter) {
                $this->recordError($errors, "filters.{$field}", "Filter [{$field}] is not allowed.");

                continue;
            }

            if (! is_array($definition)) {
                $definition = ['operator' => '=', 'value' => $definition];
            }

            $operator = strtolower((string) ($definition['operator'] ?? '='));
            $value = $definition['value'] ?? null;

            if (! in_array($operator, $this->operators(), true)) {
                $this->recordError($errors, "filters.{$field}", "Operator [{$operator}] is not supported.");

                continue;
            }

            if (! $this->isOperatorAllowedForField($model, $field, $operator)) {
                $this->recordError($errors, "filters.{$field}", "Operator [{$operator}] is not allowed for [{$field}].");

                continue;
            }

            if (in_array($operator, ['in', 'not_in'], true)) {
                $values = $this->stringOrArrayToArray($value);

                if (count($values) > $this->maxFilterValueCount()) {
                    $this->recordError(
                        $errors,
                        "filters.{$field}",
                        "Filter [{$field}] exceeds the maximum allowed list size of {$this->maxFilterValueCount()} values."
                    );

                    $value = array_slice($values, 0, $this->maxFilterValueCount());
                }
            }

            if ($operator === 'between') {
                $values = $this->stringOrArrayToArray($value);

                if (count($values) !== 2) {
                    $this->recordError($errors, "filters.{$field}", "Filter [{$field}] with [between] requires exactly two values.");

                    continue;
                }
            }

            if ($hasCustomFilter) {
                if (! $this->applyCustomFilter($query, $model, $customFilters[$field], $field, $operator, $value, $definition, $errors)) {
                    $this->recordError($errors, "filters.{$field}", "Custom filter [{$field}] could not be resolved.");
                }

                continue;
            }

            $value = $this->normalizeFilterValue($model, $field, $operator, $value);

            if (str_contains($field, '.')) {
                if (! $this->isAllowedRelationDepth($field)) {
                    $this->recordError($errors, "filters.{$field}", "Filter [{$field}] exceeds the configured maximum relation depth.");

                    continue;
                }

                $leafColumn = $this->leafColumn($field);

                $this->whereHasNested(
                    $query,
                    $field,
                    fn (Builder $relationQuery) => $this->applyOperator($relationQuery, $leafColumn, $operator, $value),
                );

                continue;
            }

            if ($tableColumns !== [] && ! in_array($field, $tableColumns, true)) {
                $this->recordError($errors, "filters.{$field}", "Column [{$field}] is not filterable because it does not exist.");

                continue;
            }

            $this->applyOperator($query, $this->qualify($model, $field), $operator, $value);
        }

        return $query;
    }

    protected function applyDateRange(Builder $query, array $params, array &$errors): Builder
    {
        $from = $params['date_from'] ?? null;
        $to = $params['date_to'] ?? null;

        if ($from === null && $to === null) {
            return $query;
        }

        $model = $query->getModel();
        $column = (string) ($params['date_column'] ?? 'created_at');
        $tableColumns = $this->tableColumns($model);

        if ($tableColumns !== [] && ! in_array($column, $tableColumns, true)) {
            $this->recordError($errors, 'date_column', "Date column [{$column}] does not exist.");

            return $query;
        }

        $qualifiedColumn = $this->qualify($model, $column);

        if ($from !== null) {
            $fromDate = $this->parseDateBoundary((string) $from, true);

            if ($fromDate !== null) {
                $query->where($qualifiedColumn, '>=', $fromDate->toDateTimeString());
            } else {
                $this->recordError($errors, 'date_from', 'The date_from value could not be parsed.');
            }
        }

        if ($to !== null) {
            $toDate = $this->parseDateBoundary((string) $to, false);

            if ($toDate !== null) {
                $query->where($qualifiedColumn, '<=', $toDate->toDateTimeString());
            } else {
                $this->recordError($errors, 'date_to', 'The date_to value could not be parsed.');
            }
        }

        return $query;
    }

    protected function applyEagerLoads(Builder $query, array $params, array &$errors): Builder
    {
        $requested = $params['with'] ?? [];

        if (! is_array($requested)) {
            $requested = $this->stringOrArrayToArray($requested);
        }

        if ($requested === []) {
            return $query;
        }

        $allowedRelations = $this->modelArrayOption($query->getModel(), 'allowedRelations');
        $safeRelations = [];

        if ($allowedRelations === []) {
            $this->recordError($errors, 'with', 'Eager loading requires an explicit [$allowedRelations] allow-list on the model.');

            return $query;
        }

        foreach ($requested as $relation) {
            if (! is_string($relation) || $relation === '') {
                continue;
            }

            if (! $this->isAllowedRelationDepth($relation)) {
                $this->recordError($errors, 'with', "Relation [{$relation}] exceeds the configured maximum depth.");

                continue;
            }

            if (! in_array($relation, $allowedRelations, true)) {
                $this->recordError($errors, 'with', "Relation [{$relation}] is not allowed for eager loading.");

                continue;
            }

            $safeRelations[] = $relation;
        }

        if ($safeRelations !== []) {
            $query->with($safeRelations);
        }

        return $query;
    }

    protected function applySorting(Builder $query, array $params, array &$errors): Builder
    {
        $model = $query->getModel();
        $fields = $this->stringOrArrayToArray($params['sort_by'] ?? $this->defaultSortBy($model));
        $directions = $this->stringOrArrayToArray($params['sort_dir'] ?? $this->defaultSortDirection($model));
        $sortable = $this->modelArrayOption($model, 'sortable');
        $tableColumns = $this->tableColumns($model);
        $joinRegistry = [];
        $appliedSorts = [];

        foreach ($fields as $index => $field) {
            if (! is_string($field) || $field === '') {
                continue;
            }

            if (! in_array($field, $sortable, true)) {
                $this->recordError($errors, 'sort_by', "Sort field [{$field}] is not allowed.");

                continue;
            }

            $direction = strtolower($directions[$index] ?? $directions[0] ?? $this->defaultSortDirection($model));
            if (! in_array($direction, ['asc', 'desc'], true)) {
                $this->recordError($errors, 'sort_dir', "Sort direction [{$direction}] is invalid.");
                $direction = 'asc';
            }

            if (str_contains($field, '.')) {
                if (count(explode('.', $field)) !== 2) {
                    $this->recordError($errors, 'sort_by', "Relation sort [{$field}] must be a one-level relation.");

                    continue;
                }

                if ($this->sortByRelation($query, $field, $direction, $joinRegistry)) {
                    $appliedSorts[] = "{$field}:{$direction}";
                } else {
                    $this->recordError($errors, 'sort_by', "Relation sort [{$field}] could not be applied.");
                }

                continue;
            }

            if ($tableColumns !== [] && ! in_array($field, $tableColumns, true)) {
                $this->recordError($errors, 'sort_by', "Sort column [{$field}] does not exist.");

                continue;
            }

            $query->orderBy($this->qualify($model, $field), $direction);
            $appliedSorts[] = "{$field}:{$direction}";
        }

        if ($appliedSorts === []) {
            $fallbackField = $this->defaultSortBy($model);
            $fallbackDirection = $this->defaultSortDirection($model);

            if (
                ! str_contains($fallbackField, '.')
                && ($tableColumns === [] || in_array($fallbackField, $tableColumns, true))
                && ($sortable === [] || in_array($fallbackField, $sortable, true))
            ) {
                $query->orderBy($this->qualify($model, $fallbackField), $fallbackDirection);
                $appliedSorts[] = "{$fallbackField}:{$fallbackDirection}";
            }
        }

        $this->rememberAppliedSorts($query, $appliedSorts);

        return $query;
    }

    protected function sortByRelation(Builder $query, string $field, string $direction, array &$joinRegistry): bool
    {
        [$relationName, $leafColumn] = explode('.', $field, 2);

        if (isset($joinRegistry[$relationName])) {
            return false;
        }

        try {
            $model = $query->getModel();
            $relation = $this->resolveRelation($model, [$relationName]);

            if ($relation === null) {
                return false;
            }

            $relatedModel = $relation->getRelated();
            $relatedColumns = $this->tableColumns($relatedModel);

            if ($relatedColumns !== [] && ! in_array($leafColumn, $relatedColumns, true)) {
                return false;
            }

            if ($query->getQuery()->columns === null) {
                $query->select($model->getTable().'.*');
            }

            if ($relation instanceof BelongsTo) {
                $alias = 'qb_sort_'.$relationName;
                $joinRegistry[$relationName] = $alias;

                $query
                    ->leftJoin(
                        $relatedModel->getTable().' as '.$alias,
                        $model->getTable().'.'.$relation->getForeignKeyName(),
                        '=',
                        $alias.'.'.$relation->getOwnerKeyName()
                    )
                    ->orderByRaw("{$alias}.{$leafColumn} IS NULL")
                    ->orderBy("{$alias}.{$leafColumn}", $direction);

                return true;
            }

            $sortAlias = 'qb_sort_value_'.$relationName.'_'.$leafColumn;
            $subquery = null;

            if ($relation instanceof HasOne) {
                $subquery = DB::table($relatedModel->getTable())
                    ->select($leafColumn)
                    ->whereColumn($relation->getForeignKeyName(), $model->getTable().'.'.$model->getKeyName())
                    ->limit(1);
            }

            if ($relation instanceof HasMany) {
                $subquery = DB::table($relatedModel->getTable())
                    ->select($leafColumn)
                    ->whereColumn($relation->getForeignKeyName(), $model->getTable().'.'.$model->getKeyName())
                    ->orderBy($leafColumn, $direction)
                    ->limit(1);
            }

            if ($relation instanceof BelongsToMany) {
                $subquery = DB::table($relatedModel->getTable())
                    ->select($relatedModel->getTable().'.'.$leafColumn)
                    ->join(
                        $relation->getTable(),
                        $relation->getTable().'.'.$relation->getRelatedPivotKeyName(),
                        '=',
                        $relatedModel->getTable().'.'.$relatedModel->getKeyName()
                    )
                    ->whereColumn(
                        $relation->getTable().'.'.$relation->getForeignPivotKeyName(),
                        $model->getTable().'.'.$model->getKeyName()
                    )
                    ->orderBy($relatedModel->getTable().'.'.$leafColumn, $direction)
                    ->limit(1);
            }

            if ($subquery === null) {
                return false;
            }

            $joinRegistry[$relationName] = true;

            $query
                ->selectSub($subquery, $sortAlias)
                ->orderByRaw("{$sortAlias} IS NULL")
                ->orderBy($sortAlias, $direction);

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    protected function applyOperator(Builder $query, string $column, string $operator, mixed $value): void
    {
        $value = is_array($value) && ! in_array($operator, ['in', 'not_in', 'between'], true)
            ? implode(',', array_map('strval', array_values($value)))
            : $value;

        match ($operator) {
            '=', '!=', '<', '>', '<=', '>=' => $query->where($column, $operator, $value),
            'like' => $query->where($column, 'LIKE', $this->likePattern((string) $value, $this->filterLikeMode())),
            'not_like' => $query->where($column, 'NOT LIKE', $this->likePattern((string) $value, $this->filterLikeMode())),
            'in' => $query->whereIn($column, $this->stringOrArrayToArray($value)),
            'not_in' => $query->whereNotIn($column, $this->stringOrArrayToArray($value)),
            'between' => $this->applyBetween($query, $column, $value),
            'null' => $query->whereNull($column),
            'not_null' => $query->whereNotNull($column),
            default => null,
        };
    }

    protected function applyBetween(Builder $query, string $column, mixed $value): void
    {
        $values = $this->stringOrArrayToArray($value);

        if (count($values) !== 2) {
            return;
        }

        $query->whereBetween($column, [$values[0], $values[1]]);
    }

    protected function whereHasNested(Builder $query, string $field, callable $callback, bool $useOrWhere = false): void
    {
        $relations = explode('.', $field);
        array_pop($relations);

        if ($relations === []) {
            return;
        }

        $buildChain = function (Builder $builder, array $remainingRelations) use ($callback, &$buildChain): void {
            $currentRelation = array_shift($remainingRelations);

            if ($currentRelation === null) {
                $callback($builder);

                return;
            }

            try {
                $builder->whereHas($currentRelation, function (Builder $relationQuery) use ($remainingRelations, $callback, $buildChain): void {
                    if ($remainingRelations === []) {
                        $callback($relationQuery);

                        return;
                    }

                    $buildChain($relationQuery, $remainingRelations);
                });
            } catch (Throwable) {
            }
        };

        $firstRelation = array_shift($relations);

        if ($firstRelation === '') {
            return;
        }

        $method = $useOrWhere ? 'orWhereHas' : 'whereHas';

        try {
            $query->{$method}($firstRelation, function (Builder $relationQuery) use ($relations, $callback, $buildChain): void {
                if ($relations === []) {
                    $callback($relationQuery);

                    return;
                }

                $buildChain($relationQuery, $relations);
            });
        } catch (Throwable) {
        }
    }

    protected function resolveRelation(Model $model, array $chain): mixed
    {
        $current = $model;
        $resolvedRelation = null;

        foreach ($chain as $relationName) {
            if (! method_exists($current, $relationName)) {
                return null;
            }

            $resolvedRelation = $current->{$relationName}();
            $current = $resolvedRelation->getRelated();
        }

        return $resolvedRelation;
    }

    protected function summarizeFilters(array $params): array
    {
        $summary = [];

        foreach (($params['filters'] ?? []) as $field => $definition) {
            if (! is_string($field)) {
                continue;
            }

            $summary['filters'][$field] = is_array($definition)
                ? (($definition['operator'] ?? '=').':'.($definition['value'] ?? ''))
                : '=:'.$definition;
        }

        if (($params['date_from'] ?? null) !== null || ($params['date_to'] ?? null) !== null) {
            $summary['date_range'] = [
                'column' => $params['date_column'] ?? 'created_at',
                'from' => $params['date_from'] ?? null,
                'to' => $params['date_to'] ?? null,
            ];
        }

        if (($params['trashed'] ?? null) !== null) {
            $summary['trashed'] = $params['trashed'];
        }

        if (($params['with'] ?? null) !== null) {
            $summary['with'] = $this->stringOrArrayToArray($params['with']);
        }

        return $summary;
    }

    protected function summarizeRequestedSorts(Model $model, array $params): array
    {
        $fields = $this->stringOrArrayToArray($params['sort_by'] ?? $this->defaultSortBy($model));
        $directions = $this->stringOrArrayToArray($params['sort_dir'] ?? $this->defaultSortDirection($model));

        return array_map(
            fn (string $field, int $index) => $field.':'.strtolower($directions[$index] ?? $directions[0] ?? $this->defaultSortDirection($model)),
            $fields,
            array_keys($fields),
        );
    }

    protected function tableColumns(Model $model): array
    {
        $connection = $model->getConnectionName() ?? config('database.default', 'default');
        $cacheKey = $connection.':'.$model->getTable();

        if (! array_key_exists($cacheKey, static::$columnCache)) {
            try {
                static::$columnCache[$cacheKey] = Schema::connection($connection)->getColumnListing($model->getTable());
            } catch (Throwable) {
                static::$columnCache[$cacheKey] = [];
            }
        }

        return static::$columnCache[$cacheKey];
    }

    protected function normalizeParams(Request|array $request): array
    {
        if (! $request instanceof Request) {
            return $request;
        }

        $params = $request->all();
        $headerParams = $this->headerParams($request);

        if ($headerParams === []) {
            return $params;
        }

        if ($this->overrideRequestValuesWithHeaders()) {
            return array_replace_recursive($params, $headerParams);
        }

        return array_replace_recursive($headerParams, $params);
    }

    protected function resolveRequestParams(Builder $query, Request|array|null $request = null): array
    {
        $errors = [];
        $model = $query->getModel();

        if ($request !== null) {
            $validated = $this->validatedRequestParams($request, $errors);
            $this->throwIfStrict($model, $errors);

            return $validated;
        }

        $remembered = $this->rememberedRequestParams($query);

        if ($remembered !== []) {
            return $remembered;
        }

        if ($this->handleRequestAutomatically()) {
            $validated = $this->validatedRequestParams(request(), $errors);
            $this->throwIfStrict($model, $errors);

            return $validated;
        }

        return [];
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     * @return array<string, mixed>
     */
    protected function validatedRequestParams(Request|array $request, array &$errors): array
    {
        $params = $this->normalizeParams($request);
        $validated = [];

        foreach ($params as $key => $value) {
            if (! is_string($key) || ! in_array($key, $this->allowedRequestKeys, true)) {
                if (is_string($key)) {
                    $this->recordError($errors, $key, "The request key [{$key}] is not supported by the query builder.");
                }

                continue;
            }

            $validated[$key] = $value;
        }

        if (array_key_exists('search', $validated)) {
            $validated['search'] = $this->validatedScalarStringValue('search', $validated['search'], $errors);
        }

        if (array_key_exists('sort_by', $validated)) {
            $validated['sort_by'] = $this->validatedListInput('sort_by', $validated['sort_by'], $errors);
        }

        if (array_key_exists('sort_dir', $validated)) {
            $validated['sort_dir'] = $this->validatedListInput('sort_dir', $validated['sort_dir'], $errors);
        }

        if (array_key_exists('columns', $validated)) {
            $validated['columns'] = $this->validatedListInput('columns', $validated['columns'], $errors);
        }

        if (array_key_exists('with', $validated)) {
            $validated['with'] = $this->validatedListInput('with', $validated['with'], $errors, true);
        }

        if (array_key_exists('page', $validated)) {
            $validated['page'] = $this->validatedPositiveInteger('page', $validated['page'], $errors);
        }

        if (array_key_exists('per_page', $validated)) {
            $validated['per_page'] = $this->validatedPositiveInteger('per_page', $validated['per_page'], $errors);
        }

        if (array_key_exists('date_from', $validated)) {
            $validated['date_from'] = $this->validatedScalarStringValue('date_from', $validated['date_from'], $errors);
        }

        if (array_key_exists('date_to', $validated)) {
            $validated['date_to'] = $this->validatedScalarStringValue('date_to', $validated['date_to'], $errors);
        }

        if (array_key_exists('date_column', $validated)) {
            $validated['date_column'] = $this->validatedScalarStringValue('date_column', $validated['date_column'], $errors);
        }

        if (array_key_exists('trashed', $validated)) {
            $validated['trashed'] = $this->validatedScalarStringValue('trashed', $validated['trashed'], $errors);
        }

        if (array_key_exists('filters', $validated)) {
            $validated['filters'] = $this->validatedFilters($validated['filters'], $errors);
        }

        return array_filter(
            $validated,
            static fn (mixed $value): bool => $value !== null
        );
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     * @param  array<string, mixed>  $response
     * @return array<string, mixed>
     */
    protected function validatedResponseOptions(array $response, array &$errors): array
    {
        $validated = [];

        if (array_key_exists('status_key', $response)) {
            $statusKey = $this->validatedResponseKey('response.status_key', $response['status_key'], $errors);

            if ($statusKey !== null) {
                $validated['status_key'] = $statusKey;
            }
        }

        if (array_key_exists('message_key', $response)) {
            $messageKey = $this->validatedResponseKey('response.message_key', $response['message_key'], $errors);

            if ($messageKey !== null) {
                $validated['message_key'] = $messageKey;
            }
        }

        if (array_key_exists('message', $response)) {
            $message = $this->validatedScalarStringValue('response.message', $response['message'], $errors);

            if ($message !== null) {
                $validated['message'] = $message;
            }
        }

        if (array_key_exists('status', $response)) {
            $validated['status'] = $response['status'];
        }

        return $validated;
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     */
    protected function validatedScalarStringValue(string $parameter, mixed $value, array &$errors): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value) || is_object($value)) {
            $this->recordError($errors, $parameter, "The [{$parameter}] value must be a scalar string value.");

            return null;
        }

        return trim((string) $value);
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     * @return array<int, string>|string|null
     */
    protected function validatedListInput(string $parameter, mixed $value, array &$errors, bool $forceArray = false): array|string|null
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            $list = [];

            foreach ($value as $item) {
                if (is_array($item) || is_object($item)) {
                    $this->recordError($errors, $parameter, "The [{$parameter}] list contains a non-scalar value.");

                    continue;
                }

                $item = trim((string) $item);

                if ($item !== '') {
                    $list[] = $item;
                }
            }

            return $list;
        }

        if (is_object($value)) {
            $this->recordError($errors, $parameter, "The [{$parameter}] value must be a string or array.");

            return null;
        }

        $stringValue = trim((string) $value);

        if ($stringValue === '') {
            return $forceArray ? [] : null;
        }

        return $forceArray ? array_values(array_filter(array_map('trim', explode(',', $stringValue)))) : $stringValue;
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     */
    protected function validatedPositiveInteger(string $parameter, mixed $value, array &$errors): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($value) || is_object($value) || filter_var($value, FILTER_VALIDATE_INT) === false) {
            $this->recordError($errors, $parameter, "The [{$parameter}] value must be a valid integer.");

            return null;
        }

        return max((int) $value, 1);
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     * @return array<string, mixed>
     */
    protected function validatedFilters(mixed $filters, array &$errors): array
    {
        if (! is_array($filters)) {
            $this->recordError($errors, 'filters', 'The [filters] value must be an associative array.');

            return [];
        }

        $validated = [];

        foreach ($filters as $field => $definition) {
            if (! is_string($field) || trim($field) === '') {
                $this->recordError($errors, 'filters', 'Each filter key must be a non-empty string.');

                continue;
            }

            $field = trim($field);

            if (! is_array($definition)) {
                if (is_object($definition)) {
                    $this->recordError($errors, "filters.{$field}", 'Filter values must be scalar or an operator/value array.');

                    continue;
                }

                $validated[$field] = $definition;

                continue;
            }

            $operator = $definition['operator'] ?? '=';
            $value = $definition['value'] ?? null;

            if (is_array($operator) || is_object($operator)) {
                $this->recordError($errors, "filters.{$field}", 'Filter operator must be a scalar value.');

                continue;
            }

            if (is_object($value)) {
                $this->recordError($errors, "filters.{$field}", 'Filter value must be scalar or array.');

                continue;
            }

            $validated[$field] = [
                'operator' => strtolower(trim((string) $operator)),
                'value' => $value,
            ];
        }

        return $validated;
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     */
    protected function validatedResponseKey(string $parameter, mixed $value, array &$errors): ?string
    {
        $key = $this->validatedScalarStringValue($parameter, $value, $errors);

        if ($key === null || $key === '') {
            return null;
        }

        if (! preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $key)) {
            $this->recordError($errors, $parameter, "The [{$parameter}] value must be a valid response key.");

            return null;
        }

        return $key;
    }

    /**
     * @return array<string, mixed>
     */
    protected function headerParams(Request $request): array
    {
        if (! $this->queryHeadersEnabled()) {
            return [];
        }

        $params = [];

        foreach ($this->queryHeaderNames() as $parameter => $headerNames) {
            $headerValue = $this->headerValue($request, $headerNames);

            if ($headerValue === null) {
                continue;
            }

            $params[$parameter] = $this->normalizeHeaderValue($parameter, $headerValue);
        }

        return $params;
    }

    /**
     * @param  array<int, string>  $headerNames
     */
    protected function headerValue(Request $request, array $headerNames): ?string
    {
        foreach ($headerNames as $headerName) {
            $value = $request->headers->get($headerName);

            if (! is_string($value)) {
                continue;
            }

            $value = trim($value);

            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    protected function normalizeHeaderValue(string $parameter, string $value): mixed
    {
        return match ($parameter) {
            'filters' => $this->decodedHeaderFilters($value),
            'sort_by', 'sort_dir', 'columns', 'with' => $this->decodedHeaderList($value),
            default => trim($value),
        };
    }

    /**
     * @return array<string, mixed>|string
     */
    protected function decodedHeaderFilters(string $value): array|string
    {
        $decoded = $this->decodedJsonHeader($value);

        if (is_array($decoded)) {
            return $decoded;
        }

        $parsed = [];
        parse_str($value, $parsed);

        return $parsed !== [] ? $parsed : trim($value);
    }

    /**
     * @return array<int, string>|string
     */
    protected function decodedHeaderList(string $value): array|string
    {
        $decoded = $this->decodedJsonHeader($value);

        if (is_array($decoded)) {
            return array_values(array_filter(array_map(
                static fn (mixed $item): string => trim((string) $item),
                $decoded
            ), static fn (string $item): bool => $item !== ''));
        }

        return trim($value);
    }

    protected function decodedJsonHeader(string $value): mixed
    {
        $trimmed = trim($value);

        if ($trimmed === '' || ! in_array($trimmed[0], ['{', '[', '"'], true)) {
            return null;
        }

        $decoded = json_decode($trimmed, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    protected function stringOrArrayToArray(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(
                fn (mixed $item) => is_string($item) ? trim($item) : (string) $item,
                $value
            ), fn (string $item) => $item !== ''));
        }

        if ($value === null || $value === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', (string) $value)), fn (string $item) => $item !== ''));
    }

    protected function modelArrayOption(Model $model, string $property): array
    {
        return (array) $this->readModelProperty($model, $property, []);
    }

    protected function defaultSortBy(Model $model): string
    {
        $value = $this->readModelProperty($model, 'defaultSortBy');

        if (is_string($value) && $value !== '') {
            return $value;
        }

        return $model->getKeyName();
    }

    protected function defaultSortDirection(Model $model): string
    {
        $direction = $this->readModelProperty(
            $model,
            'defaultSortDir',
            (string) config('query-builder.default_sort_direction', 'asc'),
        );

        return strtolower($direction) === 'desc' ? 'desc' : 'asc';
    }

    protected function resolvePerPage(Model $model, array $params): int
    {
        $default = (int) $this->readModelProperty(
            $model,
            'defaultPerPage',
            (int) config('query-builder.default_per_page', 15),
        );

        $max = (int) $this->readModelProperty(
            $model,
            'maxPerPage',
            (int) config('query-builder.max_per_page', 100),
        );

        return min(max((int) ($params['per_page'] ?? $default), 1), max($max, 1));
    }

    protected function minSearchLength(): int
    {
        return max((int) config('query-builder.min_search_length', 3), 1);
    }

    protected function maxFilterCount(): int
    {
        return max((int) config('query-builder.max_filter_count', 15), 1);
    }

    protected function maxRelationDepth(): int
    {
        return max((int) config('query-builder.max_relation_depth', 3), 1);
    }

    protected function maxFilterValueCount(): int
    {
        return max((int) config('query-builder.max_filter_value_count', 100), 1);
    }

    protected function searchLikeMode(): string
    {
        return $this->validatedLikeMode((string) config('query-builder.search_like_mode', 'contains'));
    }

    protected function filterLikeMode(): string
    {
        return $this->validatedLikeMode((string) config('query-builder.filter_like_mode', 'contains'));
    }

    protected function operators(): array
    {
        return ['=', '!=', '<', '>', '<=', '>=', 'like', 'not_like', 'in', 'not_in', 'between', 'null', 'not_null'];
    }

    protected function usesSoftDeletes(Model $model): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive($model), true);
    }

    protected function isProtectedSoftDeleteFilter(Model $model, string $field): bool
    {
        if (! $this->usesSoftDeletes($model) || str_contains($field, '.')) {
            return false;
        }

        $deletedAtColumn = method_exists($model, 'getDeletedAtColumn')
            ? $model->getDeletedAtColumn()
            : 'deleted_at';

        return $field === $deletedAtColumn;
    }

    protected function validatedLikeMode(string $mode): string
    {
        $mode = strtolower(trim($mode));

        return in_array($mode, ['contains', 'starts_with', 'ends_with', 'exact'], true)
            ? $mode
            : 'contains';
    }

    protected function likePattern(string $value, string $mode): string
    {
        return match ($mode) {
            'starts_with' => $value.'%',
            'ends_with' => '%'.$value,
            'exact' => $value,
            default => '%'.$value.'%',
        };
    }

    protected function normalizeFilterValue(Model $model, string $field, string $operator, mixed $value): mixed
    {
        if (! $this->shouldNormalizeBooleanFilter($model, $field, $operator)) {
            return $value;
        }

        if (in_array($operator, ['in', 'not_in'], true)) {
            return array_map(
                fn (mixed $item) => $this->normalizeBooleanLikeValue($item),
                $this->stringOrArrayToArray($value),
            );
        }

        return $this->normalizeBooleanLikeValue($value);
    }

    protected function shouldNormalizeBooleanFilter(Model $model, string $field, string $operator): bool
    {
        if (! in_array($operator, ['=', '!=', 'in', 'not_in'], true)) {
            return false;
        }

        $booleanFilterable = $this->modelArrayOption($model, 'booleanFilterable');

        if (in_array($field, $booleanFilterable, true)) {
            return true;
        }

        if (str_contains($field, '.')) {
            return false;
        }

        $casts = $model->getCasts();
        $cast = strtolower((string) ($casts[$field] ?? ''));

        return in_array($cast, ['bool', 'boolean'], true);
    }

    protected function normalizeBooleanLikeValue(mixed $value): mixed
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $normalized = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

            return $normalized ?? $value;
        }

        if (is_int($value)) {
            return match ($value) {
                1 => true,
                0 => false,
                default => $value,
            };
        }

        return $value;
    }

    protected function parseDateBoundary(string $value, bool $startOfDay): ?CarbonImmutable
    {
        try {
            $date = CarbonImmutable::parse($value);

            return $startOfDay ? $date->startOfDay() : $date->endOfDay();
        } catch (Throwable) {
            return null;
        }
    }

    protected function isAllowedRelationDepth(string $relation): bool
    {
        return count(explode('.', $relation)) <= $this->maxRelationDepth();
    }

    protected function leafColumn(string $field): string
    {
        $parts = explode('.', $field);

        return (string) end($parts);
    }

    protected function qualify(Model $model, string $column): string
    {
        return $model->getTable().'.'.$column;
    }

    protected function strictMode(Model $model): bool
    {
        return (bool) $this->readModelProperty(
            $model,
            'queryBuilderStrict',
            (bool) config('query-builder.strict_mode', false),
        );
    }

    protected function handleRequestAutomatically(): bool
    {
        return (bool) config('query-builder.handle_request_automatically', true);
    }

    protected function queryHeadersEnabled(): bool
    {
        return (bool) config('query-builder.query_headers.enabled', false);
    }

    protected function overrideRequestValuesWithHeaders(): bool
    {
        return (bool) config('query-builder.query_headers.override_request_values', true);
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function queryHeaderNames(): array
    {
        $configured = config('query-builder.query_headers.names', []);

        if (! is_array($configured)) {
            return [];
        }

        $headers = [];

        foreach ($configured as $parameter => $names) {
            if (! is_string($parameter) || ! in_array($parameter, $this->allowedRequestKeys, true)) {
                continue;
            }

            $normalized = array_values(array_filter(array_map(
                static fn (mixed $name): string => trim((string) $name),
                is_array($names) ? $names : [$names],
            ), static fn (string $name): bool => $name !== ''));

            if ($normalized !== []) {
                $headers[$parameter] = $normalized;
            }
        }

        return $headers;
    }

    /**
     * @param  array<string, mixed>  $response
     * @return array{status_key: string, status: mixed, message_key: string, message: mixed}
     */
    protected function resolveResponseOptions(Builder $query, array $response = []): array
    {
        $defaults = [
            'status_key' => (string) config('query-builder.response.status_key', 'status'),
            'status' => config('query-builder.response.status_value', true),
            'message_key' => (string) config('query-builder.response.message_key', 'message'),
            'message' => null,
        ];

        $remembered = $this->responseRegistry()[$this->queryObject($query)] ?? [];

        return array_replace($defaults, $remembered, $response);
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function allowedFilterOperators(Model $model): array
    {
        return (array) $this->readModelProperty($model, 'allowedFilterOperators', []);
    }

    /**
     * @return array<string, mixed>
     */
    protected function customFilters(Model $model): array
    {
        return (array) $this->readModelProperty($model, 'customFilters', []);
    }

    protected function isOperatorAllowedForField(Model $model, string $field, string $operator): bool
    {
        $allowedOperators = $this->allowedFilterOperators($model);

        if (! array_key_exists($field, $allowedOperators)) {
            return true;
        }

        return in_array($operator, (array) $allowedOperators[$field], true);
    }

    protected function applyCustomFilter(
        Builder $query,
        Model $model,
        mixed $callback,
        string $field,
        string $operator,
        mixed $value,
        array $definition,
        array &$errors,
    ): bool {
        $resolved = $this->resolveCustomFilterCallable($model, $callback);

        if ($resolved === null) {
            return false;
        }

        try {
            $resolved($query, $value, $operator, $field, $definition);

            return true;
        } catch (Throwable $exception) {
            $this->recordError($errors, "filters.{$field}", 'Custom filter failed: '.$exception->getMessage());

            return false;
        }
    }

    protected function resolveCustomFilterCallable(Model $model, mixed $callback): ?Closure
    {
        if (is_string($callback) && method_exists($model, $callback)) {
            return function (...$arguments) use ($model, $callback): mixed {
                return $this->invokeModelMethod($model, $callback, $arguments);
            };
        }

        if ($callback instanceof Closure) {
            return $callback;
        }

        if (is_callable($callback)) {
            return Closure::fromCallable($callback);
        }

        return null;
    }

    protected function invokeModelMethod(Model $model, string $method, array $arguments = []): mixed
    {
        try {
            $reflection = new ReflectionMethod($model, $method);
            $reflection->setAccessible(true);

            return $reflection->invokeArgs($model, $arguments);
        } catch (ReflectionException) {
            return null;
        }
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     */
    protected function throwIfStrict(Model $model, array $errors): void
    {
        if (! $this->strictMode($model) || $errors === []) {
            return;
        }

        throw new InvalidQueryBuilderQuery($errors);
    }

    /**
     * @param  list<array{parameter: string, reason: string}>  $errors
     */
    protected function recordError(array &$errors, string $parameter, string $reason): void
    {
        $errors[] = [
            'parameter' => $parameter,
            'reason' => $reason,
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     */
    protected function rememberRequestParams(Builder $query, array $params): void
    {
        $this->requestRegistry()[$this->queryObject($query)] = $params;
    }

    protected function rememberedRequestParams(Builder $query): array
    {
        return $this->requestRegistry()[$this->queryObject($query)] ?? [];
    }

    /**
     * @param  array<string, mixed>  $response
     */
    protected function rememberResponseOptions(Builder $query, array $response): void
    {
        if ($response === []) {
            return;
        }

        $this->responseRegistry()[$this->queryObject($query)] = $response;
    }

    /**
     * @param  list<string>  $appliedSorts
     */
    protected function rememberAppliedSorts(Builder $query, array $appliedSorts): void
    {
        $this->appliedSortRegistry()[$this->queryObject($query)] = $appliedSorts;
    }

    /**
     * @return list<string>|null
     */
    protected function appliedSortsFor(Builder $query): ?array
    {
        return $this->appliedSortRegistry()[$this->queryObject($query)] ?? null;
    }

    protected function queryObject(Builder $query): object
    {
        return $query->getQuery();
    }

    /**
     * @return WeakMap<object, list<string>>
     */
    protected function appliedSortRegistry(): WeakMap
    {
        return static::$appliedSortRegistry ??= new WeakMap;
    }

    /**
     * @return WeakMap<object, array<string, mixed>>
     */
    protected function requestRegistry(): WeakMap
    {
        return static::$requestRegistry ??= new WeakMap;
    }

    /**
     * @return WeakMap<object, array<string, mixed>>
     */
    protected function responseRegistry(): WeakMap
    {
        return static::$responseRegistry ??= new WeakMap;
    }

    protected function readModelProperty(Model $model, string $property, mixed $default = null): mixed
    {
        if (! property_exists($model, $property)) {
            return $default;
        }

        try {
            $reflection = new ReflectionProperty($model, $property);

            return $reflection->isInitialized($model)
                ? $reflection->getValue($model)
                : $default;
        } catch (ReflectionException) {
            return $default;
        }
    }
}
