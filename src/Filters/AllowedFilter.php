<?php

namespace GhostCompiler\LaravelQueryBuilder\Filters;

use Closure;
use GhostCompiler\LaravelQueryBuilder\Contracts\Filter;

class AllowedFilter
{
    public function __construct(
        public readonly string $name,
        public readonly string $type = 'exact',
        public readonly string|Closure|Filter|null $handler = null,
        public readonly ?string $column = null,
    ) {}

    public static function exact(string $name, ?string $column = null): self
    {
        return new self($name, 'exact', null, $column);
    }

    public static function partial(string $name, ?string $column = null): self
    {
        return new self($name, 'partial', null, $column);
    }

    public static function scope(string $name, ?string $scope = null): self
    {
        return new self($name, 'scope', $scope);
    }

    public static function custom(string $name, string|Closure|Filter $filter): self
    {
        return new self($name, 'custom', $filter);
    }
}
