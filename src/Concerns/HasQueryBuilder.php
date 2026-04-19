<?php

namespace GhostCompiler\LaravelQueryBuilder\Concerns;

use GhostCompiler\LaravelQueryBuilder\Support\QueryBuilderEngine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait HasQueryBuilder
{
    public static function QueryBuild(Request|array $request, array $response = []): Builder
    {
        return app(QueryBuilderEngine::class)->apply(static::query(), $request, $response);
    }

    public function scopeQueryBuilder(Builder $query, Request|array $request, array $response = []): Builder
    {
        return app(QueryBuilderEngine::class)->apply($query, $request, $response);
    }

    public function scopePaginateQuery(Builder $query, Request|array|null $request = null): LengthAwarePaginator
    {
        return app(QueryBuilderEngine::class)->paginate($query, $request);
    }

    public function scopePaginateTable(Builder $query, Request|array|null $request = null, array $response = []): array
    {
        return app(QueryBuilderEngine::class)->paginateTable($query, $request, $response);
    }
}
