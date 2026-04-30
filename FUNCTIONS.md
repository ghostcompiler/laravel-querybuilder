# Laravel Query Builder Functions Index

This file is a clickable index of public package functions and common calls. Each documentation link jumps to the matching section in [README.md](README.md).

## Fluent API

| Function | Call | Docs |
| :--- | :--- | :--- |
| `Query::for()` | `Query::for(User::class)` | [README](README.md#query-for) |
| `Query::extend()` | `Query::extend(Filter::class, ActiveUsersFilter::class)` | [README](README.md#query-extend) |
| `schema()` | `->schema(UserSchema::class)` | [README](README.md#schema) |
| `allowedFilters()` | `->allowedFilters(['email', AllowedFilter::partial('name')])` | [README](README.md#allowed-filters) |
| `allowedSorts()` | `->allowedSorts(['name', 'created_at'])` | [README](README.md#allowed-sorts) |
| `allowedIncludes()` | `->allowedIncludes(['profile', 'roles.permissions'])` | [README](README.md#allowed-includes) |
| `allowedFields()` | `->allowedFields(['id', 'name', 'email'])` | [README](README.md#allowed-fields) |
| `tenantScoped()` | `->tenantScoped()` | [README](README.md#tenant-scoped) |
| `tenantScoped(false)` | `->tenantScoped(false)` | [README](README.md#tenant-scoped) |
| `tenantScoped(true, column)` | `->tenantScoped(true, 'account_id')` | [README](README.md#tenant-scoped) |
| `request()` | `->request(request())` | [README](README.md#request) |
| `cache()` | `->cache(60)` | [README](README.md#cache) |
| `toEloquentBuilder()` | `->toEloquentBuilder()` | [README](README.md#to-eloquent-builder) |
| `get()` | `->get()` | [README](README.md#get) |
| `first()` | `->first()` | [README](README.md#first) |
| `paginate()` | `->paginate()` | [README](README.md#paginate) |
| `paginate(perPage)` | `->paginate(25)` | [README](README.md#paginate) |

## Filter Helpers

| Function | Call | Docs |
| :--- | :--- | :--- |
| `AllowedFilter::exact()` | `AllowedFilter::exact('status')` | [README](README.md#allowed-filter-exact) |
| `AllowedFilter::partial()` | `AllowedFilter::partial('name')` | [README](README.md#allowed-filter-partial) |
| `AllowedFilter::scope()` | `AllowedFilter::scope('active')` | [README](README.md#allowed-filter-scope) |
| `AllowedFilter::custom()` | `AllowedFilter::custom('active', ActiveUsersFilter::class)` | [README](README.md#allowed-filter-custom) |
| `Filter::apply()` | `public function apply(Builder $query, mixed $value)` | [README](README.md#filter-apply) |

## Schema Methods

| Function | Call | Docs |
| :--- | :--- | :--- |
| `filters()` | `public function filters(): array` | [README](README.md#query-schema-methods) |
| `sorts()` | `public function sorts(): array` | [README](README.md#query-schema-methods) |
| `includes()` | `public function includes(): array` | [README](README.md#query-schema-methods) |
| `fields()` | `public function fields(): array` | [README](README.md#query-schema-methods) |
| `filterOperators()` | `public function filterOperators(): array` | [README](README.md#query-schema-methods) |
| `customFilters()` | `public function customFilters(): array` | [README](README.md#query-schema-methods) |

## Legacy Trait API

| Function | Call | Docs |
| :--- | :--- | :--- |
| `QueryBuild()` | `User::QueryBuild($request)` | [README](README.md#trait-querybuild) |
| `queryBuilder()` | `User::query()->queryBuilder($request)` | [README](README.md#trait-querybuilder) |
| `paginateQuery()` | `User::query()->queryBuilder($request)->paginateQuery()` | [README](README.md#trait-paginate-query) |
| `paginateTable()` | `User::query()->queryBuilder($request)->paginateTable()` | [README](README.md#trait-paginate-table) |

## Exceptions

| Function | Call | Docs |
| :--- | :--- | :--- |
| `errors()` | `$exception->errors()` | [README](README.md#exception-errors) |

## Engine API

| Function | Call | Docs |
| :--- | :--- | :--- |
| `apply()` | `app(QueryBuilderEngine::class)->apply($builder, $request)` | [README](README.md#engine-methods) |
| `applyWithDefinition()` | `app(QueryBuilderEngine::class)->applyWithDefinition($builder, $request, $definition)` | [README](README.md#engine-methods) |
| `paginate()` | `app(QueryBuilderEngine::class)->paginate($builder, $request)` | [README](README.md#engine-methods) |
| `paginateTable()` | `app(QueryBuilderEngine::class)->paginateTable($builder, $request)` | [README](README.md#engine-methods) |
