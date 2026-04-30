# Laravel Query Builder

<p align="center">
  <img src="assets/logo/logo.png" alt="Laravel Query Builder Logo" width="180">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10%20to%2013-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Built%20For-Eloquent%20APIs-0F172A?style=for-the-badge" alt="Eloquent APIs">
</p>

> A Laravel package for API-ready Eloquent query building with searchable fields, nested relation filters, relation sorting, strict mode, custom filters, pagination helpers, and safer public query interfaces.

## Overview

Laravel Query Builder helps API endpoints safely accept request query parameters for filtering, sorting, relation includes, sparse fieldsets, pagination, and table-style responses.

The package is designed around explicit allow lists. A request cannot filter, sort, include, or select fields unless your model, schema, or fluent query definition permits it.

Use it when you want:

- schema-driven filtering, sorting, includes, and fields
- tenant-aware query scoping
- safe JSON:API-style query strings
- strict rejection of unknown filters, sorts, includes, and fields
- relation include validation
- optional policy-aware relation includes
- sensitive column masking before serialization
- custom filter classes and callbacks
- legacy trait-based helpers for existing models
- pagination metadata formatted for API responses

## Quick Links

- [Installation](#installation)
- [Fluent API Quick Start](#fluent-api-quick-start)
- [Schema Classes](#schema-classes)
- [JSON:API Query Syntax](#jsonapi-query-syntax)
- [Legacy Trait API](#legacy-trait-api)
- [Configuration](#configuration)
- [Security Model](#security-model)
- [Function Reference](#function-reference)
- [Functions Index](FUNCTIONS.md)

## Compatibility

- Laravel 10
- Laravel 11
- Laravel 12
- Laravel 13
- PHP 8.1+

## Installation

Install from Packagist:

```bash
composer require ghostcompiler/laravel-querybuilder
```

Laravel package discovery registers the service provider automatically.

To publish the legacy config filename:

```bash
php artisan vendor:publish --tag=query-builder-config
```

To publish the modern config filename:

```bash
php artisan vendor:publish --tag=querybuilder-config
```

Both config files contain the same defaults. The package reads both `query-builder` and `querybuilder` config keys for compatibility.

### Local Path Install

If you are developing the package locally inside another Laravel app:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/absolute/path/to/laravel-querybuilder",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "ghostcompiler/laravel-querybuilder": "*"
    }
}
```

Then run:

```bash
composer update ghostcompiler/laravel-querybuilder
```

## Fluent API Quick Start

The fluent API is the recommended interface for new code.

```php
use GhostCompiler\LaravelQueryBuilder\Query;

Route::get('/users', function () {
    return Query::for(User::class)
        ->tenantScoped()
        ->schema(UserSchema::class)
        ->allowedIncludes()
        ->allowedFilters()
        ->allowedSorts()
        ->paginate();
});
```

Example request:

```text
/users?filter[name]=john&include=roles.permissions&sort=-created_at&fields[users]=id,name,email&page[number]=1&page[size]=15
```

The fluent API runs in strict mode for its runtime definition. Unknown filters, sorts, includes, and disallowed field requests throw typed query-builder exceptions.

## Schema Classes

Schemas centralize the public query contract for a model.

```php
use GhostCompiler\LaravelQueryBuilder\QuerySchema;

class UserSchema extends QuerySchema
{
    public function filters(): array
    {
        return ['name', 'email'];
    }

    public function sorts(): array
    {
        return ['created_at', 'name'];
    }

    public function includes(): array
    {
        return ['roles.permissions', 'profile'];
    }

    public function fields(): array
    {
        return ['id', 'name', 'email'];
    }
}
```

Use the schema:

```php
Query::for(User::class)
    ->schema(UserSchema::class)
    ->paginate();
```

Schema instances are cached by class name during the request lifecycle.

## Allowed Filters

You can define filters in a schema:

```php
use GhostCompiler\LaravelQueryBuilder\Filters\AllowedFilter;

class UserSchema extends QuerySchema
{
    public function filters(): array
    {
        return [
            'email',
            AllowedFilter::exact('status'),
            AllowedFilter::partial('name'),
            AllowedFilter::scope('active'),
            AllowedFilter::custom('high_score', HighScoreFilter::class),
        ];
    }
}
```

Or define filters directly:

```php
Query::for(User::class)
    ->allowedFilters([
        'email',
        AllowedFilter::partial('name'),
        'active' => ActiveUsersFilter::class,
    ])
    ->get();
```

Supported filter styles:

- `AllowedFilter::exact('status')`
- `AllowedFilter::partial('name')`
- `AllowedFilter::scope('active')`
- `AllowedFilter::custom('active', ActiveUsersFilter::class)`
- string shorthand: `'email'`
- array shorthand: `'name' => 'partial'`
- class shorthand: `'active' => ActiveUsersFilter::class`

Query examples:

```text
/users?filter[email]=alice@example.com
/users?filter[name]=ali
/users?filter[active]=true
```

## Custom Filter Classes

Create a filter class:

```php
use GhostCompiler\LaravelQueryBuilder\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class ActiveUsersFilter implements Filter
{
    public function apply(Builder $query, mixed $value)
    {
        return $query->where('active', filter_var($value, FILTER_VALIDATE_BOOL));
    }
}
```

Register it:

```php
Query::for(User::class)
    ->allowedFilters([
        'active' => ActiveUsersFilter::class,
    ])
    ->get();
```

Custom filters are trusted code. Avoid raw SQL with unsanitized request values.

## Allowed Sorts

Schema example:

```php
public function sorts(): array
{
    return ['name', 'created_at'];
}
```

Direct example:

```php
Query::for(User::class)
    ->allowedSorts(['name', 'created_at'])
    ->get();
```

Query examples:

```text
/users?sort=name
/users?sort=-created_at
/users?sort=name,-created_at
```

The package rejects non-allow-listed sort fields with `InvalidSortException` in strict mode.

## Allowed Includes

Schema example:

```php
public function includes(): array
{
    return ['profile', 'roles.permissions'];
}
```

Direct example:

```php
Query::for(User::class)
    ->allowedIncludes(['profile', 'roles.permissions'])
    ->get();
```

Query example:

```text
/users?include=profile,roles.permissions
```

Includes are validated against:

- the explicit include allow list
- relation existence on the model
- configured maximum include depth
- optional `viewRelation` gate policy checks

## Policy-Aware Includes

If a `viewRelation` gate ability is defined, the package checks it before eager loading a requested include:

```php
use Illuminate\Support\Facades\Gate;

Gate::define('viewRelation', function ($user, $model, string $relation) {
    return $relation !== 'roles.permissions' || $user->can('viewPermissions');
});
```

If the gate denies the relation:

- strict includes enabled: `UnauthorizedRelationException`
- strict includes disabled: relation is skipped silently

If no `viewRelation` ability exists, the package assumes route/controller/model authorization is handled by the application.

## Sparse Fieldsets

Schema example:

```php
public function fields(): array
{
    return ['id', 'name', 'email'];
}
```

Request example:

```text
/users?fields[users]=id,name,email
```

Sparse fieldsets are validated against allowed fields and masked columns. The model primary key is automatically kept when needed.

## Column Masking

Configure sensitive columns:

```php
'masked_columns' => [
    'users' => ['password', 'remember_token'],
    'oauth_clients' => ['secret'],
],
```

When `mask_sensitive_columns` is enabled, matching attributes are hidden before serialization, including loaded relations.

Masked columns are also removed from selectable sparse fieldsets.

## Tenant Scoping

Tenant scoping is enabled by default for the fluent API if the model table has the configured tenant column.

```php
Query::for(User::class)
    ->tenantScoped()
    ->schema(UserSchema::class)
    ->get();
```

This applies:

```php
where('tenant_id', auth()->user()->tenant_id)
```

Disable it for a trusted system query:

```php
Query::for(User::class)
    ->tenantScoped(false)
    ->schema(UserSchema::class)
    ->get();
```

Use a custom tenant column:

```php
Query::for(User::class)
    ->tenantScoped(true, 'account_id')
    ->schema(UserSchema::class)
    ->get();
```

Or configure it globally:

```php
'tenant_column' => 'account_id',
```

## JSON:API Query Syntax

The fluent API normalizes JSON:API-style request parameters:

```text
filter[name]=john
include=roles.permissions
sort=-created_at
fields[users]=id,name,email
page[number]=1
page[size]=15
```

Equivalent normalized structure:

```php
[
    'filters' => ['name' => 'john'],
    'with' => ['roles.permissions'],
    'sort_by' => ['created_at'],
    'sort_dir' => ['desc'],
    'fields' => ['users' => ['id', 'name', 'email']],
    'page' => 1,
    'per_page' => 15,
]
```

Legacy parameter names are still supported:

```text
filters[name]=john
with=roles.permissions
sort_by=created_at
sort_dir=desc
columns=id,name,email
page=1
per_page=15
```

## Pagination

The fluent `paginate()` method returns a JSON-friendly array:

```php
$payload = Query::for(User::class)
    ->schema(UserSchema::class)
    ->paginate();
```

Shape:

```php
[
    'data' => [...],
    'meta' => [
        'total' => 50,
        'per_page' => 15,
        'current_page' => 1,
        'last_page' => 4,
    ],
    'links' => [
        'first' => '...',
        'last' => '...',
        'prev' => null,
        'next' => '...',
    ],
]
```

Override per-page:

```php
Query::for(User::class)
    ->schema(UserSchema::class)
    ->paginate(25);
```

## Query Cache

Cache a terminal operation:

```php
Query::for(User::class)
    ->schema(UserSchema::class)
    ->cache(60)
    ->paginate();
```

The cache key includes the operation, model, request parameters, and runtime definition.

## Fluent Terminal Methods

```php
Query::for(User::class)->schema(UserSchema::class)->get();
Query::for(User::class)->schema(UserSchema::class)->first();
Query::for(User::class)->schema(UserSchema::class)->paginate();
Query::for(User::class)->schema(UserSchema::class)->toEloquentBuilder();
```

`get()`, `first()`, and `paginate()` execute the query. `toEloquentBuilder()` applies the request rules and returns the underlying Eloquent builder for further trusted server-side work.

## Legacy Trait API

Existing projects can keep using the model trait.

```php
use GhostCompiler\LaravelQueryBuilder\Concerns\HasQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasQueryBuilder;

    protected array $searchable = ['name', 'email', 'profile.bio'];
    protected array $filterable = ['status', 'roles.name'];
    protected array $sortable = ['name', 'created_at', 'profile.city'];
    protected array $selectable = ['id', 'name', 'email'];
    protected array $allowedRelations = ['profile', 'roles.permissions'];
}
```

Static builder:

```php
$query = User::QueryBuild($request);
```

Local scope:

```php
$query = User::query()->queryBuilder($request);
```

Paginator:

```php
$paginator = User::query()->queryBuilder($request)->paginateQuery();
```

Table payload:

```php
$payload = User::query()->queryBuilder($request)->paginateTable();
```

The legacy trait API uses model properties and `query-builder.strict_mode` to decide whether invalid input throws or is ignored.

## Legacy Model Options

Supported model properties:

```php
protected array $searchable = ['name', 'email', 'profile.bio'];
protected array $filterable = ['status', 'roles.name'];
protected array $sortable = ['name', 'created_at'];
protected array $selectable = ['id', 'name', 'email'];
protected array $allowedRelations = ['profile', 'roles.permissions'];
protected array $allowedFilterOperators = [
    'status' => ['=', 'in'],
];
protected array $dateFilterable = ['created_at'];
protected array $customFilters = [
    'high_score' => 'applyHighScoreFilter',
];
protected string $defaultSortBy = 'created_at';
protected string $defaultSortDir = 'desc';
protected int $defaultPerPage = 15;
protected int $maxPerPage = 100;
protected bool $queryBuilderStrict = true;
```

Custom model callback:

```php
protected function applyHighScoreFilter($query, mixed $value): void
{
    if (filter_var($value, FILTER_VALIDATE_BOOL)) {
        $query->where('score', '>=', 90);
    }
}
```

## Legacy Request Parameters

| Parameter | Example | Purpose |
| :--- | :--- | :--- |
| `search` | `?search=alice` | Global search across `$searchable`. |
| `filters` | `?filters[status]=active` | Allow-listed field filters. |
| `sort_by` | `?sort_by=created_at` | Allow-listed sort fields. |
| `sort_dir` | `?sort_dir=desc` | Sort direction. |
| `with` | `?with=profile,roles` | Allow-listed eager loads. |
| `columns` | `?columns=id,name,email` | Allow-listed selected columns. |
| `page` | `?page=2` | Page number. |
| `per_page` | `?per_page=25` | Page size, capped by config/model max. |
| `date_from` | `?date_from=2025-01-01` | Start date boundary. |
| `date_to` | `?date_to=2025-12-31` | End date boundary. |
| `date_column` | `?date_column=created_at` | Date column, validated by allow list. |
| `trashed` | `?trashed=with` | Soft-delete handling: `with` or `only`. |

## Filter Operators

Legacy operator syntax:

```text
filters[score][operator]=>=
filters[score][value]=90
```

Supported operators:

- `=`
- `!=`
- `<`
- `>`
- `<=`
- `>=`
- `like`
- `not_like`
- `in`
- `not_in`
- `between`
- `null`
- `not_null`

Restrict operators per field:

```php
protected array $allowedFilterOperators = [
    'status' => ['=', 'in'],
    'score' => ['>=', 'between'],
];
```

## Header-Based Query Input

Enable headers:

```php
'query_headers' => [
    'enabled' => true,
    'override_request_values' => true,
],
```

Example headers:

```text
X-Query-Search: Alice
X-Query-Filter: {"status":"active"}
X-Query-Sort: created_at
X-Query-Sort-Dir: desc
X-Query-With: profile
X-Query-Per-Page: 15
```

Header values are normalized and validated through the same engine as query-string parameters.

## Configuration

Default config:

```php
return [
    'strict_mode' => false,
    'deny_unknown_filters' => true,
    'deny_unknown_sorts' => true,
    'deny_unknown_includes' => true,
    'handle_request_automatically' => true,
    'tenant_scoping_enabled' => true,
    'tenant_column' => 'tenant_id',
    'mask_sensitive_columns' => true,
    'masked_columns' => [],
    'strict_includes' => true,
    'policy_aware_includes' => true,
    'query_headers' => [
        'enabled' => false,
        'override_request_values' => true,
        'names' => [
            'search' => ['X-Query-Search'],
            'filters' => ['X-Query-Filters', 'X-Query-Filter'],
            'sort_by' => ['X-Query-Sort-By', 'X-Query-Sort'],
            'sort_dir' => ['X-Query-Sort-Dir'],
            'page' => ['X-Query-Page'],
            'per_page' => ['X-Query-Per-Page'],
            'date_from' => ['X-Query-Date-From'],
            'date_to' => ['X-Query-Date-To'],
            'date_column' => ['X-Query-Date-Column'],
            'columns' => ['X-Query-Columns'],
            'with' => ['X-Query-With', 'X-Query-Include', 'X-Query-Includes'],
            'trashed' => ['X-Query-Trashed'],
        ],
    ],
    'response' => [
        'status_key' => 'status',
        'status_value' => true,
        'message_key' => 'message',
    ],
    'search_like_mode' => 'contains',
    'filter_like_mode' => 'contains',
    'default_per_page' => 15,
    'max_per_page' => 100,
    'default_sort_direction' => 'asc',
    'min_search_length' => 3,
    'max_filter_count' => 15,
    'max_filter_value_count' => 100,
    'max_relation_depth' => 3,
    'max_include_depth' => 3,
    'cache_prefix' => 'laravel-querybuilder',
];
```

Important options:

- `strict_mode`: makes the legacy trait API throw on invalid query input.
- `tenant_scoping_enabled`: enables default tenant scoping in the fluent API when the tenant column exists.
- `tenant_column`: tenant column used with `auth()->user()`.
- `mask_sensitive_columns`: hides configured columns before serialization.
- `masked_columns`: table or model keyed sensitive columns.
- `strict_includes`: controls whether policy-denied includes throw or are skipped.
- `policy_aware_includes`: enables `Gate::allows('viewRelation', [$model, $relation])`.
- `max_include_depth`: caps dotted include depth.
- `max_relation_depth`: legacy relation-depth cap for dotted relation paths.
- `cache_prefix`: prefix for fluent query cache keys.

## Exception Types

All package exceptions extend `QueryBuilderException`.

| Exception | When it is used |
| :--- | :--- |
| `InvalidFilterException` | Unknown filter, unsupported operator, invalid filter shape, or disallowed filter. |
| `InvalidIncludeException` | Unknown, disallowed, or missing relation include. |
| `InvalidSortException` | Unknown or disallowed sort field/direction. |
| `UnauthorizedRelationException` | `viewRelation` gate denies an include in strict include mode. |
| `IncludeDepthExceededException` | Requested include exceeds configured depth. |
| `InvalidQueryBuilderQuery` | General invalid query-builder input. |

Read validation errors:

```php
try {
    Query::for(User::class)->schema(UserSchema::class)->get();
} catch (InvalidQueryBuilderQuery $exception) {
    return response()->json([
        'errors' => $exception->errors(),
    ], 422);
}
```

## Security Model

The package is secure-by-default for query shaping in the fluent API:

- filters must be allow-listed
- sorts must be allow-listed
- includes must be allow-listed
- sparse fieldsets must be allow-listed
- masked columns are hidden before serialization
- nested includes are depth-limited
- requested includes must exist as model relations
- optional policy checks can block specific relations
- standard values are passed through Eloquent/PDO bindings

The package does not replace:

- user authentication
- route middleware
- controller authorization
- model policies
- business-specific data visibility rules

Custom filters are application code. Keep them parameterized and avoid unsafe raw SQL.

## Performance Notes

- Include only relations that are needed by the endpoint.
- Index columns used by filters and sorts.
- Keep `max_include_depth` low for public APIs.
- Use sparse fieldsets to reduce payload size.
- Use cache only for endpoints where user, tenant, and permission context are safe to cache.
- Prefer schema classes for stable public query contracts.

## Function Reference

The full clickable method list is also available in [FUNCTIONS.md](FUNCTIONS.md).

<a id="query-for"></a>
### `Query::for()`

Create a fluent query builder for a model class or an existing Eloquent builder.

```php
Query::for(User::class);
Query::for(User::query()->where('active', true));
```

<a id="query-extend"></a>
### `Query::extend()`

Register or read extension values by contract name.

```php
Query::extend(Filter::class, ActiveUsersFilter::class);
$extensions = Query::extend(Filter::class);
```

<a id="schema"></a>
### `schema()`

Attach a `QuerySchema` class or instance.

```php
Query::for(User::class)->schema(UserSchema::class);
```

<a id="allowed-filters"></a>
### `allowedFilters()`

Override schema filters directly.

```php
Query::for(User::class)->allowedFilters(['email', AllowedFilter::partial('name')]);
```

<a id="allowed-sorts"></a>
### `allowedSorts()`

Override schema sorts directly.

```php
Query::for(User::class)->allowedSorts(['name', 'created_at']);
```

<a id="allowed-includes"></a>
### `allowedIncludes()`

Override schema includes directly.

```php
Query::for(User::class)->allowedIncludes(['profile', 'roles.permissions']);
```

<a id="allowed-fields"></a>
### `allowedFields()`

Override schema sparse fieldset columns directly.

```php
Query::for(User::class)->allowedFields(['id', 'name', 'email']);
```

<a id="tenant-scoped"></a>
### `tenantScoped()`

Enable, disable, or customize tenant isolation.

```php
Query::for(User::class)->tenantScoped();
Query::for(User::class)->tenantScoped(false);
Query::for(User::class)->tenantScoped(true, 'account_id');
```

<a id="request"></a>
### `request()`

Provide request input manually.

```php
Query::for(User::class)->request(request());
Query::for(User::class)->request(['filter' => ['name' => 'Alice']]);
```

<a id="cache"></a>
### `cache()`

Cache a terminal query operation for the given number of seconds.

```php
Query::for(User::class)->schema(UserSchema::class)->cache(60)->paginate();
```

<a id="to-eloquent-builder"></a>
### `toEloquentBuilder()`

Apply request rules and return the underlying Eloquent builder.

```php
$builder = Query::for(User::class)->schema(UserSchema::class)->toEloquentBuilder();
```

<a id="get"></a>
### `get()`

Execute and return an Eloquent collection.

```php
$users = Query::for(User::class)->schema(UserSchema::class)->get();
```

<a id="first"></a>
### `first()`

Execute and return the first model or `null`.

```php
$user = Query::for(User::class)->schema(UserSchema::class)->first();
```

<a id="paginate"></a>
### `paginate()`

Execute and return a JSON-friendly pagination array.

```php
$payload = Query::for(User::class)->schema(UserSchema::class)->paginate();
$payload = Query::for(User::class)->schema(UserSchema::class)->paginate(25);
```

<a id="allowed-filter-exact"></a>
### `AllowedFilter::exact()`

Allow exact matching for a filter.

```php
AllowedFilter::exact('status');
```

<a id="allowed-filter-partial"></a>
### `AllowedFilter::partial()`

Allow partial `LIKE` matching for a filter.

```php
AllowedFilter::partial('name');
```

<a id="allowed-filter-scope"></a>
### `AllowedFilter::scope()`

Call an Eloquent local scope.

```php
AllowedFilter::scope('active');
AllowedFilter::scope('active_users', 'active');
```

<a id="allowed-filter-custom"></a>
### `AllowedFilter::custom()`

Use a custom filter class, instance, callback, or callable.

```php
AllowedFilter::custom('active', ActiveUsersFilter::class);
```

<a id="filter-apply"></a>
### `Filter::apply()`

Implement a custom filter.

```php
public function apply(Builder $query, mixed $value)
{
    return $query->where('active', true);
}
```

<a id="query-schema-methods"></a>
### `QuerySchema` Methods

Define allowed query surface.

```php
public function filters(): array;
public function sorts(): array;
public function includes(): array;
public function fields(): array;
public function filterOperators(): array;
public function customFilters(): array;
```

<a id="trait-querybuild"></a>
### `QueryBuild()`

Legacy static trait entry point.

```php
$query = User::QueryBuild($request);
```

<a id="trait-querybuilder"></a>
### `queryBuilder()`

Legacy local scope.

```php
$query = User::query()->queryBuilder($request);
```

<a id="trait-paginate-query"></a>
### `paginateQuery()`

Legacy paginator scope.

```php
$paginator = User::query()->queryBuilder($request)->paginateQuery();
```

<a id="trait-paginate-table"></a>
### `paginateTable()`

Legacy table response scope.

```php
$payload = User::query()->queryBuilder($request)->paginateTable();
```

<a id="exception-errors"></a>
### `InvalidQueryBuilderQuery::errors()`

Read validation error details from a thrown query-builder exception.

```php
$errors = $exception->errors();
```

<a id="engine-methods"></a>
### `QueryBuilderEngine` Methods

Advanced service-level entry points used internally by the fluent and trait APIs.

```php
app(QueryBuilderEngine::class)->apply($builder, $request);
app(QueryBuilderEngine::class)->applyWithDefinition($builder, $request, $definition);
app(QueryBuilderEngine::class)->paginate($builder, $request);
app(QueryBuilderEngine::class)->paginateTable($builder, $request);
```

## Testing

Run tests:

```bash
composer test
```

Run formatting:

```bash
composer format
```

Run lint checks:

```bash
composer lint
```

Run static analysis:

```bash
composer stan
```

Run the full quality suite:

```bash
composer quality
```

Optional PostgreSQL test runs can be configured through environment variables:

```bash
TEST_DB_CONNECTION=pgsql
TEST_DB_HOST=127.0.0.1
TEST_DB_PORT=5432
TEST_DB_DATABASE=laravel_querybuilder_test
TEST_DB_USERNAME=postgres
TEST_DB_PASSWORD=secret
composer test
```

## Quality and Security

Additional package docs:

- [Development Guide](DEVELOPMENT.md)
- [Quality Guide](QUALITY.md)
- [Security Policy](SECURITY.md)

## License

MIT

## Development And Build Environment

This package was developed using **ServBay** as the local development environment.

### Development Tool Used

- Local development tool: `ServBay`
- Website: [www.servbay.com](https://www.servbay.com/)

### ServBay Assets

ServBay logo and icon are included in this repository for README rendering.

<p>
  <img src="assets/servbay/servbay-icon-blue-512.svg" alt="ServBay Icon" width="96">
</p>

### Testing And Build Machine

- Tested on: `Mac M4`
- Built on: `Mac M4`
