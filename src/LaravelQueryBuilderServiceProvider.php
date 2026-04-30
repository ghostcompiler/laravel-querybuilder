<?php

namespace GhostCompiler\LaravelQueryBuilder;

use Illuminate\Support\ServiceProvider;

class LaravelQueryBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/query-builder.php', 'query-builder');
        $this->mergeConfigFrom(__DIR__.'/../config/querybuilder.php', 'querybuilder');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/query-builder.php' => config_path('query-builder.php'),
        ], 'query-builder-config');

        $this->publishes([
            __DIR__.'/../config/querybuilder.php' => config_path('querybuilder.php'),
        ], 'querybuilder-config');
    }
}
