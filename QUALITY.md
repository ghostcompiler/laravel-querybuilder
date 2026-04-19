# Quality Guide

This package uses a layered quality approach so package changes stay stable across Laravel 10 through 13.

The runtime package supports PHP 8.1+, but the local quality toolchain is intended to run on PHP 8.2+ because current Larastan releases require that.

## Quality Checks

- `composer analyse`
  Validates `composer.json`.

- `composer test`
  Runs the PHPUnit package test suite through Orchestra Testbench.

- `composer lint`
  Verifies formatting with Laravel Pint.

- `composer stan`
  Runs PHPStan static analysis.

- `composer quality`
  Runs the combined local quality suite.

## CI Workflows

- `tests`
  Runs the package against the Laravel support matrix.

- `quality`
  Runs formatting and static analysis checks.

- `security`
  Runs Composer audit checks.

## Design Goals

- keep invalid query behavior explicit
- make public API usage predictable
- allow package consumers to opt into strict mode
- support extension points without forcing app-specific forks
- keep documentation aligned with tested behavior
