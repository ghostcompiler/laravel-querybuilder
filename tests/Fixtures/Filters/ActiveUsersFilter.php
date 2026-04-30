<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Filters;

use GhostCompiler\LaravelQueryBuilder\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class ActiveUsersFilter implements Filter
{
    public function apply(Builder $query, mixed $value)
    {
        $query->where($query->getModel()->qualifyColumn('is_active'), filter_var($value, FILTER_VALIDATE_BOOL));
    }
}
