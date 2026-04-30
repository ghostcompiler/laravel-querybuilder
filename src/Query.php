<?php

namespace GhostCompiler\LaravelQueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Traits\Macroable;

class Query
{
    use Macroable;

    /**
     * @var array<string, list<mixed>>
     */
    protected static array $extensions = [];

    public static function for(string|Builder $subject): QueryBuilder
    {
        return new QueryBuilder($subject);
    }

    public static function extend(string $contract, mixed $extension = null): mixed
    {
        if ($extension === null) {
            return static::$extensions[$contract] ?? [];
        }

        static::$extensions[$contract] ??= [];
        static::$extensions[$contract][] = $extension;

        return null;
    }
}
