<?php

namespace GhostCompiler\LaravelQueryBuilder;

use Closure;
use GhostCompiler\LaravelQueryBuilder\Contracts\Filter;
use GhostCompiler\LaravelQueryBuilder\Filters\AllowedFilter;
use GhostCompiler\LaravelQueryBuilder\Support\QueryBuilderEngine;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use Throwable;

class QueryBuilder
{
    use Macroable;

    protected EloquentBuilder $builder;

    protected Request|array|null $request = null;

    protected QuerySchema|string|null $schema = null;

    protected ?array $filterDefinitions = null;

    protected ?array $sortDefinitions = null;

    protected ?array $includeDefinitions = null;

    protected ?array $fieldDefinitions = null;

    protected ?bool $tenantScoped = null;

    protected ?string $tenantColumn = null;

    protected ?int $cacheSeconds = null;

    protected ?EloquentBuilder $appliedBuilder = null;

    /**
     * @var array<class-string, QuerySchema>
     */
    protected static array $schemaCache = [];

    public function __construct(string|EloquentBuilder $subject)
    {
        if ($subject instanceof EloquentBuilder) {
            $this->builder = $subject;

            return;
        }

        if (! is_subclass_of($subject, Model::class)) {
            throw new InvalidArgumentException('Query::for() expects an Eloquent model class or builder.');
        }

        $this->builder = $subject::query();
    }

    public function schema(string|QuerySchema $schema): self
    {
        $this->schema = $schema;
        $this->appliedBuilder = null;

        return $this;
    }

    public function allowedFilters(?array $filters = null): self
    {
        if ($filters !== null) {
            $this->filterDefinitions = $filters;
        }

        return $this;
    }

    public function allowedSorts(?array $sorts = null): self
    {
        if ($sorts !== null) {
            $this->sortDefinitions = $sorts;
        }

        return $this;
    }

    public function allowedIncludes(?array $includes = null): self
    {
        if ($includes !== null) {
            $this->includeDefinitions = $includes;
        }

        return $this;
    }

    public function allowedFields(?array $fields = null): self
    {
        if ($fields !== null) {
            $this->fieldDefinitions = $fields;
        }

        return $this;
    }

    public function tenantScoped(bool $enabled = true, ?string $column = null): self
    {
        $this->tenantScoped = $enabled;
        $this->tenantColumn = $column;
        $this->appliedBuilder = null;

        return $this;
    }

    public function request(Request|array $request): self
    {
        $this->request = $request;
        $this->appliedBuilder = null;

        return $this;
    }

    public function cache(int $seconds): self
    {
        $this->cacheSeconds = max($seconds, 1);

        return $this;
    }

    public function toEloquentBuilder(): EloquentBuilder
    {
        return $this->apply();
    }

    public function get(array|string $columns = ['*']): EloquentCollection
    {
        return $this->remember('get', function () use ($columns): EloquentCollection {
            $results = $this->apply()->get($columns);
            $this->maskResult($results);

            return $results;
        });
    }

    public function first(array|string $columns = ['*']): ?Model
    {
        return $this->remember('first', function () use ($columns): ?Model {
            $model = $this->apply()->first($columns);
            $this->maskResult($model);

            return $model;
        });
    }

    /**
     * @return array{data: array<int, mixed>, meta: array<string, int>, links: array<string, string|null>}
     */
    public function paginate(?int $perPage = null): array
    {
        return $this->remember('paginate', function () use ($perPage): array {
            $params = $this->requestParams();

            if ($perPage !== null) {
                $params['per_page'] = $perPage;
            }

            $paginator = app(QueryBuilderEngine::class)->paginate($this->apply(), $params);
            $items = $paginator->items();
            $this->maskResult($items);

            return [
                'data' => $items,
                'meta' => [
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                ],
                'links' => [
                    'first' => $paginator->url(1),
                    'last' => $paginator->url($paginator->lastPage()),
                    'prev' => $paginator->previousPageUrl(),
                    'next' => $paginator->nextPageUrl(),
                ],
            ];
        });
    }

    protected function apply(): EloquentBuilder
    {
        if ($this->appliedBuilder instanceof EloquentBuilder) {
            return $this->appliedBuilder;
        }

        $this->applyTenantScope();

        return $this->appliedBuilder = app(QueryBuilderEngine::class)->applyWithDefinition(
            $this->builder,
            $this->requestParams(),
            $this->definition(),
        );
    }

    protected function applyTenantScope(): void
    {
        if (! $this->tenantScopeEnabled()) {
            return;
        }

        $column = $this->tenantColumn();
        $model = $this->builder->getModel();

        if (! $this->tableHasColumn($model, $column)) {
            return;
        }

        $tenantId = $this->tenantId($column);

        if ($tenantId === null || $tenantId === '') {
            $this->builder->whereRaw('1 = 0');

            return;
        }

        $this->builder->where($model->qualifyColumn($column), $tenantId);
    }

    protected function requestParams(): Request|array
    {
        if ($this->request !== null) {
            return $this->request;
        }

        try {
            return request()->all();
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function definition(): array
    {
        $schema = $this->schemaDefinition();
        $filters = $this->buildFilterDefinition($this->filterDefinitions ?? $schema['filters']);

        return [
            'filterable' => $filters['filterable'],
            'customFilters' => array_replace($schema['customFilters'], $filters['customFilters']),
            'allowedFilterOperators' => array_replace($filters['allowedFilterOperators'], $schema['filterOperators']),
            'sortable' => $this->normalizeList($this->sortDefinitions ?? $schema['sorts']),
            'allowedRelations' => $this->normalizeList($this->includeDefinitions ?? $schema['includes']),
            'selectable' => $this->normalizeList($this->fieldDefinitions ?? $schema['fields']),
            'queryBuilderStrict' => true,
        ];
    }

    /**
     * @return array{filters: array, sorts: array, includes: array, fields: array, filterOperators: array, customFilters: array}
     */
    protected function schemaDefinition(): array
    {
        $schema = $this->schemaInstance();

        if (! $schema instanceof QuerySchema) {
            return [
                'filters' => [],
                'sorts' => [],
                'includes' => [],
                'fields' => [],
                'filterOperators' => [],
                'customFilters' => [],
            ];
        }

        return [
            'filters' => $schema->filters(),
            'sorts' => $schema->sorts(),
            'includes' => $schema->includes(),
            'fields' => $schema->fields(),
            'filterOperators' => $schema->filterOperators(),
            'customFilters' => $schema->customFilters(),
        ];
    }

    protected function schemaInstance(): ?QuerySchema
    {
        if ($this->schema instanceof QuerySchema) {
            return $this->schema;
        }

        if (is_string($this->schema)) {
            return static::$schemaCache[$this->schema] ??= app($this->schema);
        }

        return null;
    }

    /**
     * @return array{filterable: list<string>, customFilters: array<string, mixed>, allowedFilterOperators: array<string, list<string>>}
     */
    protected function buildFilterDefinition(array $filters): array
    {
        $filterable = [];
        $customFilters = [];
        $operators = [];

        foreach ($filters as $key => $definition) {
            $allowed = $this->normalizeAllowedFilter($key, $definition);

            if (! $allowed instanceof AllowedFilter) {
                continue;
            }

            if ($allowed->type === 'exact' && ($allowed->column === null || $allowed->column === $allowed->name)) {
                $filterable[] = $allowed->name;
                $operators[$allowed->name] = ['=', '!=', 'in', 'not_in'];

                continue;
            }

            $customFilters[$allowed->name] = $this->filterCallback($allowed);
        }

        return [
            'filterable' => array_values(array_unique($filterable)),
            'customFilters' => $customFilters,
            'allowedFilterOperators' => $operators,
        ];
    }

    protected function normalizeAllowedFilter(int|string $key, mixed $definition): ?AllowedFilter
    {
        if ($definition instanceof AllowedFilter) {
            return $definition;
        }

        if (is_int($key) && is_string($definition) && $definition !== '') {
            return AllowedFilter::exact($definition);
        }

        if (! is_string($key) || $key === '') {
            return null;
        }

        if ($definition instanceof Closure || $definition instanceof Filter || (is_string($definition) && class_exists($definition))) {
            return AllowedFilter::custom($key, $definition);
        }

        if (is_string($definition) && in_array($definition, ['exact', 'partial', 'scope'], true)) {
            return new AllowedFilter($key, $definition);
        }

        if (is_array($definition)) {
            $type = (string) ($definition['type'] ?? 'exact');
            $column = isset($definition['column']) ? (string) $definition['column'] : null;

            if ($type === 'custom' && isset($definition['filter'])) {
                return AllowedFilter::custom($key, $definition['filter']);
            }

            if ($type === 'partial') {
                return AllowedFilter::partial($key, $column);
            }

            if ($type === 'scope') {
                return AllowedFilter::scope($key, isset($definition['scope']) ? (string) $definition['scope'] : null);
            }

            return AllowedFilter::exact($key, $column);
        }

        return AllowedFilter::exact($key);
    }

    protected function filterCallback(AllowedFilter $filter): mixed
    {
        if ($filter->type === 'custom') {
            return $filter->handler;
        }

        if ($filter->type === 'partial') {
            $column = $filter->column ?? $filter->name;

            return static function (EloquentBuilder $query, mixed $value) use ($column): void {
                $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], (string) $value);

                $query->where($query->getModel()->qualifyColumn($column), 'LIKE', '%'.$escaped.'%');
            };
        }

        if ($filter->type === 'scope') {
            $scope = is_string($filter->handler) && $filter->handler !== ''
                ? $filter->handler
                : $filter->name;

            return static function (EloquentBuilder $query, mixed $value) use ($scope): void {
                $value === null ? $query->{$scope}() : $query->{$scope}($value);
            };
        }

        $column = $filter->column ?? $filter->name;

        return static function (EloquentBuilder $query, mixed $value) use ($column): void {
            is_array($value)
                ? $query->whereIn($query->getModel()->qualifyColumn($column), $value)
                : $query->where($query->getModel()->qualifyColumn($column), '=', $value);
        };
    }

    /**
     * @return list<string>
     */
    protected function normalizeList(array $values): array
    {
        $list = [];

        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $item = $value;
            } else {
                $item = $key;
            }

            if (! is_scalar($item)) {
                continue;
            }

            $item = trim((string) $item);

            if ($item !== '') {
                $list[] = $item;
            }
        }

        return array_values(array_unique($list));
    }

    protected function tenantScopeEnabled(): bool
    {
        return $this->tenantScoped ?? (bool) $this->configValue('tenant_scoping_enabled', true);
    }

    protected function tenantColumn(): string
    {
        return $this->tenantColumn ?: (string) $this->configValue('tenant_column', 'tenant_id');
    }

    protected function tenantId(string $tenantColumn): mixed
    {
        try {
            $user = auth()->user();
        } catch (Throwable) {
            return null;
        }

        if (! is_object($user)) {
            return null;
        }

        return $user->{$tenantColumn} ?? $user->tenant_id ?? null;
    }

    protected function tableHasColumn(Model $model, string $column): bool
    {
        try {
            $connection = $model->getConnectionName() ?? config('database.default', 'default');

            return Schema::connection($connection)->hasColumn($model->getTable(), $column);
        } catch (Throwable) {
            return false;
        }
    }

    protected function remember(string $operation, Closure $callback): mixed
    {
        if ($this->cacheSeconds === null) {
            return $callback();
        }

        try {
            return app('cache')->remember($this->cacheKey($operation), $this->cacheSeconds, $callback);
        } catch (Throwable) {
            return $callback();
        }
    }

    protected function cacheKey(string $operation): string
    {
        $payload = [
            'operation' => $operation,
            'model' => get_class($this->builder->getModel()),
            'request' => $this->requestParams() instanceof Request ? $this->requestParams()->all() : $this->requestParams(),
            'definition' => $this->definition(),
        ];

        return $this->configValue('cache_prefix', 'laravel-querybuilder').':'.sha1(json_encode($payload));
    }

    protected function maskResult(mixed $value): void
    {
        if (! (bool) $this->configValue('mask_sensitive_columns', true)) {
            return;
        }

        if ($value instanceof Model) {
            $this->maskModel($value);

            return;
        }

        if ($value instanceof LengthAwarePaginatorContract) {
            $this->maskResult($value->items());

            return;
        }

        if ($value instanceof Collection || is_array($value)) {
            foreach ($value as $item) {
                $this->maskResult($item);
            }
        }
    }

    protected function maskModel(Model $model): void
    {
        $columns = $this->maskedColumns($model);

        if ($columns !== []) {
            $model->makeHidden($columns);
        }

        foreach ($model->getRelations() as $relation) {
            $this->maskResult($relation);
        }
    }

    /**
     * @return list<string>
     */
    protected function maskedColumns(Model $model): array
    {
        $configured = $this->configValue('masked_columns', []);

        if (! is_array($configured)) {
            return [];
        }

        $columns = $configured[$model->getTable()] ?? $configured[get_class($model)] ?? [];

        return array_values(array_filter(array_map(
            static fn (mixed $column): string => trim((string) $column),
            is_array($columns) ? $columns : [$columns],
        ), static fn (string $column): bool => $column !== ''));
    }

    protected function configValue(string $key, mixed $default = null): mixed
    {
        return config('querybuilder.'.$key, config('query-builder.'.$key, $default));
    }
}
