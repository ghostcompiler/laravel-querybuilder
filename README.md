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

Laravel Query Builder is built to help you expose flexible index endpoints without rewriting the same search, filter, sort, pagination, and eager-load logic in every controller.

It gives you:

- one trait for reusable model query handling
- global search on model and nested relation columns
- per-field filters with supported operators
- one-level relation sorting
- eager-load allow lists
- selective column output
- soft-delete handling
- API-ready pagination metadata
- optional strict mode for invalid query parameters
- custom filter callbacks for app-specific logic
- per-field allowed filter operators
- deny-by-default request allow-lists for filters, sorts, includes, and selected columns
- central request sanitization and validation before query clauses are applied

## Compatibility

- Laravel 10
- Laravel 11
- Laravel 12
- Laravel 13
- PHP 8.1+

## Installation

### Install from Packagist

```bash
composer require ghostcompiler/laravel-querybuilder
```

### Install from a local package path

If you are developing this package locally and want to use it inside a Laravel app before publishing it to Packagist, add a path repository to the consuming Laravel app's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/absolute/path/to/laravel-querybuilder"
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

If you want live local changes to reflect immediately, enable symlinks:

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
    ]
}
```

## Package Setup

Laravel package discovery is already enabled through Composer, so the service provider is discovered automatically.

If you want to publish the config file:

```bash
php artisan vendor:publish --tag=query-builder-config
```

That publishes:

```text
config/query-builder.php
```

## Config File

The package config supports these defaults:

```php
return [
    'strict_mode' => false,
    'handle_request_automatically' => true,
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
];
```

Meaning of the request-handling option:

- `handle_request_automatically`
  When `true`, `paginateQuery()` and `paginateTable()` can fall back to Laravel's current request automatically if no explicit request was passed and nothing was already remembered from `queryBuilder($request)`.
- `query_headers.enabled`
  When `true`, the package also reads query-builder instructions from configured request headers.
- `query_headers.override_request_values`
  When `true`, matching query-builder headers override URL/request values. When `false`, request query values win and headers only fill missing keys.
- `query_headers.names`
  Lets you rename which headers map to `search`, `filters`, `sort_by`, `with`, `page`, and the other supported query-builder parameters.
- `strict_mode`
  When `true`, invalid request keys, invalid request shapes, invalid operators, invalid includes, invalid sort fields, and similar request problems throw an exception instead of being silently ignored.
- `response.status_value`
  Controls the default value returned in the `status` field. You can keep it as `true` or change it to a custom value like `'success'`.
- `response.status_key`
  Lets you rename the response status key if your API uses a different key name.
- `response.message_key`
  Lets you rename the response message key.
- `search_like_mode`
  Controls how global search builds the `LIKE` pattern. Supported values: `contains`, `starts_with`, `ends_with`, `exact`.
- `filter_like_mode`
  Controls how `like` and `not_like` filters build their `LIKE` patterns.
- `max_filter_value_count`
  Caps the number of values allowed in `in` and `not_in` style filters to avoid oversized query lists.
- `max_relation_depth`
  Limits how deep dotted relation paths can go for request-driven includes, filters, and searchable relation fields.

## Step 1: Add the Trait to a Model

```php
<?php

namespace App\Models;

use GhostCompiler\LaravelQueryBuilder\Concerns\HasQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasQueryBuilder;
    use SoftDeletes;

    protected array $searchable = [
        'name',
        'email',
        'profile.bio',
        'roles.name',
        'roles.permissions.name',
    ];

    protected array $sortable = [
        'id',
        'name',
        'created_at',
        'profile.city',
    ];

    protected array $selectable = [
        'id',
        'name',
        'email',
    ];

    protected array $filterable = [
        'status',
        'score',
        'created_at',
        'roles.name',
        'profile.country',
        'profile.is_public',
    ];

    protected array $dateFilterable = [
        'created_at',
    ];

    protected array $allowedRelations = [
        'profile',
        'roles',
        'roles.permissions',
        'posts',
    ];

    protected array $allowedFilterOperators = [
        'status' => ['=', 'in'],
        'score' => ['=', '>=', 'between'],
        'roles.name' => ['='],
        'profile.country' => ['='],
        'profile.is_public' => ['=', '!=', 'in', 'not_in'],
        'is_high_score' => ['='],
    ];

    protected array $customFilters = [
        'is_high_score' => 'applyHighScoreFilter',
    ];

    protected bool $queryBuilderStrict = false;

    protected int $defaultPerPage = 15;
    protected int $maxPerPage = 100;
    protected string $defaultSortBy = 'created_at';
    protected string $defaultSortDir = 'desc';

    protected function applyHighScoreFilter($query, mixed $value): void
    {
        if (filter_var($value, FILTER_VALIDATE_BOOL)) {
            $query->where('users.score', '>=', 90);
        }
    }
}
```

## Step 2: Use It in a Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController
{
    public function index(Request $request)
    {
        return User::queryBuilder($request)->paginateTable();
    }
}
```

Backward-compatible static usage also works:

```php
User::QueryBuild($request)->paginateTable();
```

But you no longer need to pass it twice.

If you want to add a custom response message once and keep the package response format:

```php
User::queryBuilder($request, [
    'message' => 'Users fetched successfully.',
])->paginateTable();
```

If you want fully automatic request handling:

```php
User::query()->paginateTable();
```

That works when `handle_request_automatically` is enabled in config.

## Step 3: Available Query Parameters

These parameters can be passed through:

- `User::queryBuilder($request)`
- the current Laravel request if `handle_request_automatically` is enabled
- configured request headers if `query_headers.enabled` is enabled

Security note:

- `search` only works for fields in `$searchable`
- `filters` only work for fields in `$filterable` or `$customFilters`
- `date_column` only works for fields in `$dateFilterable` when that allow-list is defined, otherwise it falls back to `$filterable`
- `sort_by` only works for fields in `$sortable`
- `columns` only works for fields in `$selectable`
- `with` only works for relations in `$allowedRelations`
- soft-delete state should be controlled through `trashed`, not direct `deleted_at` filters

If you do not define those allow-lists, the package now denies those request-driven features by default instead of allowing everything.

## Step 4: Request Validation and Sanitization

Before the package touches the query, it sanitizes and validates incoming request parameters.

What is validated:

- unsupported top-level query-builder keys are rejected in strict mode
- `page` and `per_page` must be valid integers
- `filters` must be an associative array
- `with` must be a string or array of strings
- `sort_by`, `sort_dir`, and `columns` must be strings or string arrays
- `search`, `date_from`, `date_to`, `date_column`, and `trashed` must be scalar values
- response keys like `status_key` and `message_key` are validated before use
- large `in` and `not_in` filter lists are capped to the configured safe limit
- dotted relation filters and search fields are capped by `max_relation_depth`

This means the package does not blindly trust `request()->all()` and inject values directly into query methods.

### Header-based query overrides

If you want to keep the URL clean, avoid very long query strings, or send richer query-builder instructions from React, you can enable header support:

```php
'query_headers' => [
    'enabled' => true,
    'override_request_values' => true,
],
```

Supported by default:

- `X-Query-Search`
- `X-Query-Filter` or `X-Query-Filters`
- `X-Query-Sort` or `X-Query-Sort-By`
- `X-Query-Sort-Dir`
- `X-Query-Page`
- `X-Query-Per-Page`
- `X-Query-Date-From`
- `X-Query-Date-To`
- `X-Query-Date-Column`
- `X-Query-Columns`
- `X-Query-With`, `X-Query-Include`, `X-Query-Includes`
- `X-Query-Trashed`

Recommended header payload formats:

- scalar values as plain strings
- list values like `with` or `columns` as comma-separated strings or JSON arrays
- `filters` as a JSON object

Example:

```http
X-Query-Search: admin
X-Query-Sort: created_at
X-Query-Sort-Dir: desc
X-Query-Per-Page: 20
X-Query-Filter: {"status":"active","roles.name":{"operator":"=","value":"Admin"}}
X-Query-With: ["profile","roles.permissions"]
```

Precedence rule:

- if `override_request_values=true`, headers win over matching URL/request keys
- if `override_request_values=false`, URL/request keys win and headers only fill missing values

This also avoids reserved-key collisions because query-builder commands stay at the top level while model data filters stay inside `filters[...]` or `X-Query-Filter`.

### Strict validation behavior

If strict mode is enabled:

```php
'strict_mode' => true,
```

the package throws:

```php
GhostCompiler\LaravelQueryBuilder\Exceptions\InvalidQueryBuilderQuery
```

for invalid request shapes or disallowed query operations.

If strict mode is disabled, invalid values are sanitized or ignored and the query continues safely.

### Basic pagination

```text
?page=2
?per_page=25
```

`per_page` is always clamped to the configured `max_per_page` or the model's `$maxPerPage` value, so oversized requests cannot force unbounded pagination sizes.

### Global search

```text
?search=john
```

This searches across the fields listed in `$searchable`.

Search conditions are wrapped in their own grouped `where (...)` clause, so when search is combined with filters the resulting logic stays scoped correctly:

```text
(filters...) AND (search-column-1 OR search-column-2 OR relation-search...)
```

For large production datasets, especially on related tables, prefer indexed and selective columns in `$searchable`.

### Sorting

```text
?sort_by=name
?sort_by=name,created_at&sort_dir=asc,desc
?sort_by=profile.city&sort_dir=asc
```

Relation sorting supports one-level relation paths like `profile.city`.

### Basic filters

```text
?filters[status]=active
?filters[score][operator]=>=&filters[score][value]=90
?filters[score][operator]=between&filters[score][value]=50,100
```

Large `in` / `not_in` lists are capped by `max_filter_value_count`.
Direct filtering on `deleted_at` is intentionally blocked; use `trashed=with` or `trashed=only` instead.

### Relation filters

```text
?filters[roles.name]=Admin
?filters[profile.country]=DE
?filters[profile.is_public]=true
```

Boolean relation filters are normalized automatically when the related model defines boolean casts.

### Custom filters

```text
?filters[is_high_score]=1
```

### Date range

```text
?date_from=2025-01-01
?date_to=2025-12-31
?date_column=created_at
```

If you define `$dateFilterable`, the `date_column` value must be in that allow-list. If you do not define `$dateFilterable`, the package falls back to the model's `$filterable` allow-list.

### Column selection

```text
?columns=id,name,email
```

The model primary key is automatically kept in the selected output.
Column selection requires an explicit `$selectable` allow-list.

### Eager loading

```text
?with[]=profile
?with[]=roles.permissions
```

Eager loading requires an explicit `$allowedRelations` allow-list.

### Soft deletes

```text
?trashed=with
?trashed=only
```

## Filter Operators

Supported operators:

```text
=
!=
<
>
<=
>=
like
not_like
in
not_in
between
null
not_null
```

You can globally support these operators while still restricting per-field usage with `$allowedFilterOperators`.

## Strict Mode

Strict mode helps when you want invalid query parameters to fail loudly instead of being ignored silently.

You can enable it globally in config:

```php
'strict_mode' => true,
```

Or enable it per model:

```php
protected bool $queryBuilderStrict = true;
```

When strict mode is enabled, invalid filters, sorts, eager loads, date columns, or similar invalid query inputs throw:

```php
GhostCompiler\LaravelQueryBuilder\Exceptions\InvalidQueryBuilderQuery
```

You can catch it in your app and return your own API response format.

Example:

```php
use GhostCompiler\LaravelQueryBuilder\Exceptions\InvalidQueryBuilderQuery;

public function index(Request $request)
{
    try {
        return User::queryBuilder($request)->paginateTable();
    } catch (InvalidQueryBuilderQuery $exception) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid query parameters.',
            'errors' => $exception->errors(),
        ], 422);
    }
}
```

## Custom Filter Callbacks

Custom filters are useful when a filter does not map cleanly to one database column.

Example:

```php
protected array $customFilters = [
    'is_high_score' => 'applyHighScoreFilter',
];

protected function applyHighScoreFilter($query, mixed $value, string $operator, string $field, array $definition): void
{
    if (filter_var($value, FILTER_VALIDATE_BOOL)) {
        $query->where('users.score', '>=', 90);
    }
}
```

This lets you expose readable public API filters while keeping the SQL logic private.

If a custom filter throws an exception, strict mode surfaces it as an `InvalidQueryBuilderQuery` entry using the filter key, for example `filters.is_high_score`, together with the underlying failure message.

## Per-Field Operator Rules

You can control which operators are allowed on which fields:

```php
protected array $allowedFilterOperators = [
    'status' => ['=', 'in'],
    'score' => ['=', '>=', 'between'],
    'roles.name' => ['='],
];
```

This is useful when:

- some fields should never use `like`
- enum-style fields should only allow exact match
- public APIs should avoid expensive or unsafe query shapes

## LIKE Pattern Modes

Both search and `like` filters support configurable wildcard modes:

```php
'search_like_mode' => 'contains',
'filter_like_mode' => 'contains',
```

Supported values:

- `contains`
- `starts_with`
- `ends_with`
- `exact`

For large production datasets, `starts_with` is often safer than `contains` because leading wildcards can force full table scans.

## Response Shape with `paginateTable()`

The package can return a table-style response structure:

```php
return User::queryBuilder($request)->paginateTable();
```

Example shape:

```json
{
  "status": true,
  "data": [],
  "pagination": {
    "total": 0,
    "per_page": 15,
    "current_page": 1,
    "last_page": 1,
    "from": null,
    "to": null,
    "has_more": false,
    "links": {
      "first": null,
      "last": null,
      "prev": null,
      "next": null
    }
  },
  "meta": {
    "search": null,
    "applied_sorts": [],
    "applied_filters": []
  }
}
```

If you pass a custom message:

```php
return User::queryBuilder($request, [
    'message' => 'Users fetched successfully.',
])->paginateTable();
```

Then the payload also includes:

```json
{
  "status": true,
  "message": "Users fetched successfully."
}
```

If you want the status value to be a string instead of a boolean, change config:

```php
'response' => [
    'status_key' => 'status',
    'status_value' => 'success',
    'message_key' => 'message',
],
```

You can also override response metadata per call:

```php
return User::queryBuilder($request, [
    'status' => 'success',
    'message' => 'Users fetched successfully.',
])->paginateTable();
```

## Full Example

```php
public function index(Request $request)
{
    return User::queryBuilder($request)->paginateTable();
}
```

Example request:

```text
/api/users?search=admin&filters[status]=active&filters[roles.name]=Admin&sort_by=created_at&sort_dir=desc&with[]=profile&page=1&per_page=20
```

## Safe Model Checklist

For production APIs, your model should usually define:

- `$searchable`
- `$filterable`
- `$sortable`
- `$selectable`
- `$allowedRelations`
- `$allowedFilterOperators`
- `$customFilters` when needed

This package is now deny-by-default for request-driven filters, includes, sorts, and selected columns unless those allow-lists are explicitly defined.

## Security and Usage

This package validates and applies request-driven query instructions safely, but it does not replace application-level authorization or tenant scoping.

### Always start from a safe base query

The safest pattern is to scope the base query first, then let the package apply allowed search, filter, sort, and pagination behavior on top of it.

Safe:

```php
public function index(Request $request)
{
    return $request->user()
        ->videos()
        ->queryBuilder($request)
        ->paginateTable();
}
```

Also safe:

```php
public function index(Request $request)
{
    return Video::query()
        ->where('account_id', $request->user()->account_id)
        ->queryBuilder($request)
        ->paginateTable();
}
```

Less safe for multi-tenant apps:

```php
public function index(Request $request)
{
    return Video::queryBuilder($request)->paginateTable();
}
```

If your app needs tenant boundaries, ownership checks, team scoping, or policy-based restrictions, apply those constraints before using the query builder.

### Soft deletes must go through `trashed`

Direct request-driven filtering on `deleted_at` is intentionally blocked. Use:

- `?trashed=with`
- `?trashed=only`

This keeps soft-delete behavior explicit and prevents request parameters from manipulating the soft-delete column directly.

### Relation depth is intentionally capped

Request-driven dotted relation paths are limited by `max_relation_depth` for:

- eager loading
- relation filters
- searchable relation fields

This helps prevent overly deep relation chains from turning into expensive queries.

Standard relation paths and `morphMany` relation paths are covered by the current test suite for search, filtering, and eager loading. Relation sorting is still intentionally focused on the standard one-level relation types supported by the package.

### Index the fields you expose

If a field is used in:

- `$filterable`
- `$sortable`
- `$searchable` for prefix or exact search use cases

you should strongly consider indexing it at the database level. The package can validate and build safe queries, but database indexes are still what make those queries fast in production.

### Header mode is optional

If you enable `query_headers.enabled`, the package can read query-builder instructions from headers like `X-Query-Search` and `X-Query-Filter`.

That is useful when:

- you want cleaner shareable URLs
- query strings become too long
- a frontend wants to send structured filter payloads without switching to POST

If both headers and request query parameters are present, precedence is controlled by `query_headers.override_request_values`.

### Long-lived workers are supported

The package uses `WeakMap`-backed registries for remembered request and response metadata, so temporary builder state follows the lifecycle of the underlying query object instead of lingering across long-lived PHP worker processes.

### Aliased base queries are supported

The package supports base queries that rename the root table with `from('table as alias')`. Qualification, filtering, sorting, and soft-delete handling now follow the active base-table reference instead of assuming the raw model table name.

Example:

```php
User::query()
    ->from('users as members')
    ->queryBuilder($request)
    ->paginateTable();
```

## Testing

This package includes:

- Orchestra Testbench
- an in-memory SQLite test database
- fixture models and relations
- morph relation coverage for request-driven search, filters, and eager loading
- CI coverage for Laravel 10 through 13

The package runtime supports PHP 8.1+, while the local static-analysis quality stack is intended to run on PHP 8.2+ because current Larastan releases require that.

Run tests:

```bash
composer test
```

Run the full quality suite:

```bash
composer quality
```

Optional PostgreSQL test runs can also be configured through environment variables in local development:

```bash
TEST_DB_CONNECTION=pgsql
TEST_DB_HOST=127.0.0.1
TEST_DB_PORT=5432
TEST_DB_DATABASE=laravel_querybuilder_test
TEST_DB_USERNAME=postgres
TEST_DB_PASSWORD=secret
composer test
```

The package test harness supports this mode, but you need a reachable PostgreSQL server and a prepared test database on your machine.

## Quality and Security

Additional package docs:

- [Development Guide](DEVELOPMENT.md)
- [Quality Guide](QUALITY.md)
- [Security Policy](SECURITY.md)

## License

MIT
