<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models;

class RiskyUser extends User
{
    protected array $searchable = [
        'roles.permissions.roles.name',
    ];

    protected array $filterable = [
        'deleted_at',
        'roles.permissions.roles.name',
    ];
}
