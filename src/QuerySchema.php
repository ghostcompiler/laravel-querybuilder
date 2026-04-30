<?php

namespace GhostCompiler\LaravelQueryBuilder;

abstract class QuerySchema
{
    public function filters(): array
    {
        return [];
    }

    public function sorts(): array
    {
        return [];
    }

    public function includes(): array
    {
        return [];
    }

    public function fields(): array
    {
        return [];
    }

    public function filterOperators(): array
    {
        return [];
    }

    public function customFilters(): array
    {
        return [];
    }
}
