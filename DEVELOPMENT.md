# Development Guide

## Purpose

This document is for developers who want to:

- work on this package locally
- use this package inside another Laravel app before publishing to Packagist
- run tests, formatting, and static analysis
- contribute features or fixes safely

## Local Development Setup

Clone the package:

```bash
git clone git@github.com:ghostcompiler/laravel-querybuilder.git laravel-querybuilder
cd laravel-querybuilder
```

Install dependencies:

```bash
composer update
```

Quality tooling currently targets PHP 8.2+ because the latest Larastan release requires it. The package itself still supports PHP 8.1+ at runtime.

## Use the Package in Another Laravel Project

If you want to pull this package directly into a Laravel app from your local machine, add a path repository to the consuming app's `composer.json`:

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

Then install or refresh it:

```bash
composer update ghostcompiler/laravel-querybuilder
```

If you want Composer to symlink instead of mirroring the package, you can use:

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

When you change package classes or move internals during local development, it is also a good idea to refresh autoload metadata in the consuming app:

```bash
composer dump-autoload
```

## Security Notes

This package is designed to safely transform request input into Eloquent query constraints, but contributors should treat it as a boundary-sensitive library.

Keep these rules in mind when changing behavior:

- start from a safe base query in consuming apps
- do not rely on this package to create tenant scoping or authorization automatically
- keep request-driven filters, sorts, includes, and selected columns deny-by-default unless explicitly allow-listed
- keep soft-delete behavior behind the `trashed` command instead of request-driven `deleted_at` filters
- keep relation-depth limits in place for request-driven dotted relation paths
- avoid introducing raw SQL paths that accept user-controlled identifiers or expressions

Safe consuming-app pattern:

```php
$request->user()
    ->videos()
    ->queryBuilder($request)
    ->paginateTable();
```

Less safe multi-tenant pattern:

```php
Video::queryBuilder($request)->paginateTable();
```

If you add new request-driven behavior, make sure it is:

1. validated before query application
2. covered by explicit allow-lists where applicable
3. tested in both normal mode and strict mode
4. documented in `README.md`

If the new behavior affects performance-sensitive query shapes, also consider:

- pagination caps
- relation depth
- list-size caps for `in` style filters
- whether the exposed fields should be indexed in production databases

## Package Scripts

Validate the package manifest:

```bash
composer analyse
```

Run the test suite:

```bash
composer test
```

Run static analysis:

```bash
composer stan
```

Check formatting:

```bash
composer lint
```

Auto-format:

```bash
composer format
```

Run the full local quality suite:

```bash
composer quality
```

## Test Coverage Notes

The package test suite uses Orchestra Testbench with an in-memory SQLite database.

Current fixture coverage includes:

- model search
- nested relation search
- scalar filters
- relation filters
- relation sorting
- selective columns
- soft deletes
- date ranges
- API-style pagination payloads
- custom filter callbacks
- strict mode validation

## Adding New Features

When adding a new query option:

1. Add the behavior in `src/Support/QueryBuilderEngine.php`.
2. Add model-facing configuration if needed.
3. Add or extend tests in `tests/QueryBuilderTest.php`.
4. Update `README.md`.
5. Run `composer quality`.

## Publishing Notes

Before tagging a release:

1. Update `README.md` if the API changed.
2. Run `composer quality`.
3. Check GitHub Actions for `tests`, `quality`, and `security`.
4. Tag the release with semantic versioning.
