<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Schemas;

use GhostCompiler\LaravelQueryBuilder\Filters\AllowedFilter;
use GhostCompiler\LaravelQueryBuilder\QuerySchema;

class UserSchema extends QuerySchema
{
    public function filters(): array
    {
        return [
            'email',
            AllowedFilter::partial('name'),
        ];
    }

    public function sorts(): array
    {
        return ['created_at', 'name'];
    }

    public function includes(): array
    {
        return ['profile', 'roles.permissions'];
    }

    public function fields(): array
    {
        return ['id', 'name', 'email'];
    }
}
