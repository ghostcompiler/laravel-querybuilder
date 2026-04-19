<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests;

use GhostCompiler\LaravelQueryBuilder\Exceptions\InvalidQueryBuilderQuery;
use GhostCompiler\LaravelQueryBuilder\Support\QueryBuilderEngine;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\OpenUser;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\RiskyUser;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class QueryBuilderTest extends TestCase
{
    public function test_it_searches_across_model_and_nested_relation_columns(): void
    {
        $results = User::QueryBuild(['search' => 'writer'])
            ->pluck('name')
            ->all();

        $this->assertSame(['Bob Smith'], $results);
    }

    public function test_it_searches_across_morph_many_relation_columns(): void
    {
        $results = User::QueryBuild(['search' => 'Moderation'])
            ->pluck('name')
            ->all();

        $this->assertSame(['Alice Doe'], $results);
    }

    public function test_it_filters_by_scalar_and_relation_fields(): void
    {
        $results = User::QueryBuild([
            'filters' => [
                'status' => 'active',
                'roles.name' => [
                    'operator' => '=',
                    'value' => 'Admin',
                ],
            ],
        ])->pluck('name')->all();

        $this->assertSame(['Alice Doe'], $results);
    }

    public function test_it_filters_by_morph_many_relation_fields(): void
    {
        $results = User::QueryBuild([
            'filters' => [
                'comments.is_visible' => 'true',
            ],
        ])->pluck('name')->all();

        $this->assertSame(['Alice Doe'], $results);
    }

    public function test_it_normalizes_boolean_string_filters_from_frontend_clients(): void
    {
        $results = User::QueryBuild([
            'filters' => [
                'is_active' => 'false',
            ],
            'trashed' => 'with',
        ])->pluck('name')->all();

        $this->assertSame(['Charlie Ray'], $results);
    }

    public function test_it_normalizes_boolean_string_filters_on_related_models(): void
    {
        $results = User::QueryBuild([
            'filters' => [
                'profile.is_public' => 'true',
            ],
        ])->pluck('name')->all();

        $this->assertSame(['Alice Doe'], $results);
    }

    public function test_it_sorts_by_related_fields(): void
    {
        $results = User::QueryBuild([
            'sort_by' => 'profile.city',
            'sort_dir' => 'asc',
        ])->pluck('name')->all();

        $this->assertSame(['Alice Doe', 'Bob Smith'], $results);
    }

    public function test_it_limits_selected_columns_and_keeps_the_primary_key(): void
    {
        $user = User::QueryBuild([
            'columns' => 'name,email',
            'sort_by' => 'id',
            'sort_dir' => 'asc',
        ])->first();

        $this->assertNotNull($user);
        $this->assertSame(['name', 'email', 'id'], array_keys($user->getAttributes()));
    }

    public function test_it_handles_soft_deleted_records_and_date_ranges(): void
    {
        $deletedUsers = User::QueryBuild([
            'trashed' => 'only',
            'date_from' => '2025-04-01',
            'date_to' => '2025-06-01',
        ])->pluck('name')->all();

        $this->assertSame(['Charlie Ray'], $deletedUsers);
    }

    public function test_it_respects_date_column_allow_lists(): void
    {
        config()->set('query-builder.strict_mode', true);

        try {
            User::QueryBuild([
                'date_from' => '2025-01-01',
                'date_column' => 'email',
            ])->get();

            $this->fail('Expected strict mode to block non-allow-listed date columns.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('date_column', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('not allowed', $exception->errors()[0]['reason']);
        }
    }

    public function test_it_supports_base_query_aliases_for_filters_and_sorting(): void
    {
        $results = User::query()
            ->from('users as members')
            ->queryBuilder([
                'filters' => [
                    'status' => 'active',
                ],
                'sort_by' => 'id',
                'sort_dir' => 'asc',
            ])
            ->pluck('members.name')
            ->all();

        $this->assertSame(['Alice Doe', 'Bob Smith'], $results);
    }

    public function test_it_returns_a_structured_paginate_table_payload(): void
    {
        $query = User::QueryBuild([
            'per_page' => 1,
            'search' => 'admin',
            'sort_by' => 'created_at',
            'sort_dir' => 'asc',
            'with' => ['profile', 'roles.permissions'],
            'filters' => [
                'status' => 'active',
            ],
        ]);

        $payload = app(QueryBuilderEngine::class)->paginateTable($query);

        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('pagination', $payload);
        $this->assertArrayHasKey('meta', $payload);
        $this->assertTrue($payload['status']);
        $this->assertSame(1, $payload['pagination']['per_page']);
        $this->assertSame(['created_at:asc'], $payload['meta']['applied_sorts']);
        $this->assertSame('admin', $payload['meta']['search']);
        $this->assertSame(['profile', 'roles.permissions'], $payload['meta']['applied_filters']['with']);
        $this->assertCount(1, $payload['data']);
        $this->assertArrayNotHasKey('message', $payload);
    }

    public function test_it_eager_loads_allowed_morph_many_relations(): void
    {
        $user = User::QueryBuild([
            'with' => ['comments'],
        ])->where('name', 'Alice Doe')->first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->relationLoaded('comments'));

        $comments = $user->getRelation('comments');

        $this->assertInstanceOf(EloquentCollection::class, $comments);
        $this->assertCount(1, $comments);
    }

    public function test_it_can_resolve_the_current_request_automatically(): void
    {
        request()->replace([
            'filters' => [
                'status' => 'active',
            ],
            'sort_by' => 'name',
            'sort_dir' => 'asc',
            'per_page' => 1,
        ]);

        $payload = User::query()->paginateTable();

        $this->assertSame(1, $payload['pagination']['per_page']);
        $this->assertSame(['name:asc'], $payload['meta']['applied_sorts']);
        $this->assertSame('=:active', $payload['meta']['applied_filters']['filters']['status']);
    }

    public function test_it_can_disable_automatic_request_resolution_from_config(): void
    {
        config()->set('query-builder.handle_request_automatically', false);
        request()->replace([
            'per_page' => 1,
        ]);

        $payload = User::query()->paginateTable();

        $this->assertSame(2, $payload['pagination']['per_page']);
    }

    public function test_it_can_resolve_query_parameters_from_headers_when_enabled(): void
    {
        config()->set('query-builder.query_headers.enabled', true);
        request()->replace([]);
        request()->headers->replace([
            'X-Query-Search' => 'Alice',
            'X-Query-Filter' => json_encode([
                'status' => 'active',
            ], JSON_THROW_ON_ERROR),
            'X-Query-Sort' => 'name',
            'X-Query-Sort-Dir' => 'asc',
            'X-Query-Per-Page' => '1',
        ]);

        $payload = User::query()->paginateTable();

        $this->assertSame(1, $payload['pagination']['per_page']);
        $this->assertSame('Alice', $payload['meta']['search']);
        $this->assertSame(['name:asc'], $payload['meta']['applied_sorts']);
        $this->assertSame('=:active', $payload['meta']['applied_filters']['filters']['status']);
        $this->assertCount(1, $payload['data']);
        $this->assertSame('Alice Doe', $payload['data'][0]->name);
    }

    public function test_it_can_keep_request_query_values_when_header_override_is_disabled(): void
    {
        config()->set('query-builder.query_headers.enabled', true);
        config()->set('query-builder.query_headers.override_request_values', false);
        request()->replace([
            'per_page' => 2,
            'sort_by' => 'created_at',
        ]);
        request()->headers->replace([
            'X-Query-Per-Page' => '1',
            'X-Query-Sort' => 'name',
        ]);

        $payload = User::query()->paginateTable();

        $this->assertSame(2, $payload['pagination']['per_page']);
        $this->assertSame(['created_at:desc'], $payload['meta']['applied_sorts']);
    }

    public function test_it_can_customize_status_value_from_config(): void
    {
        config()->set('query-builder.response.status_value', 'success');

        $query = User::QueryBuild([
            'per_page' => 1,
        ]);

        $payload = app(QueryBuilderEngine::class)->paginateTable($query);

        $this->assertSame('success', $payload['status']);
    }

    public function test_it_can_include_a_custom_message_from_the_query_builder_call(): void
    {
        $query = User::QueryBuild([
            'per_page' => 1,
        ], [
            'message' => 'Users fetched successfully.',
        ]);

        $payload = app(QueryBuilderEngine::class)->paginateTable($query);

        $this->assertSame(true, $payload['status']);
        $this->assertSame('Users fetched successfully.', $payload['message']);
    }

    public function test_it_supports_custom_filter_callbacks(): void
    {
        $results = User::QueryBuild([
            'filters' => [
                'is_high_score' => true,
            ],
        ])->pluck('name')->all();

        $this->assertSame(['Alice Doe'], $results);
    }

    public function test_it_throws_in_strict_mode_for_invalid_filter_operators(): void
    {
        config()->set('query-builder.strict_mode', true);

        try {
            User::QueryBuild([
                'filters' => [
                    'status' => [
                        'operator' => 'like',
                        'value' => 'active',
                    ],
                ],
            ])->get();

            $this->fail('Expected strict mode to throw an exception.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('filters.status', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('not allowed', $exception->errors()[0]['reason']);
        }
    }

    public function test_it_validates_request_shape_before_applying_the_query(): void
    {
        config()->set('query-builder.strict_mode', true);

        try {
            User::QueryBuild([
                'filters' => 'not-an-array',
                'page' => 'abc',
                'with' => (object) ['profile' => true],
            ])->get();

            $this->fail('Expected invalid request shape to throw in strict mode.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $parameters = array_column($exception->errors(), 'parameter');

            $this->assertContains('filters', $parameters);
            $this->assertContains('page', $parameters);
            $this->assertContains('with', $parameters);
        }
    }

    public function test_it_validates_automatic_request_resolution_before_paginating(): void
    {
        config()->set('query-builder.strict_mode', true);
        request()->replace([
            'page' => 'wrong',
        ]);

        try {
            app(QueryBuilderEngine::class)->paginateTable(User::query());

            $this->fail('Expected invalid automatic request parameters to throw in strict mode.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('page', $exception->errors()[0]['parameter']);
        }
    }

    public function test_it_caps_large_in_filters_to_a_safe_configured_size(): void
    {
        config()->set('query-builder.max_filter_value_count', 2);

        $results = User::QueryBuild([
            'filters' => [
                'status' => [
                    'operator' => 'in',
                    'value' => ['active', 'inactive', 'pending'],
                ],
            ],
        ])->pluck('name')->all();

        $this->assertSame(['Bob Smith', 'Alice Doe'], $results);
    }

    public function test_it_supports_a_configurable_prefix_search_mode(): void
    {
        config()->set('query-builder.search_like_mode', 'starts_with');

        $results = User::QueryBuild([
            'search' => 'Ali',
        ])->pluck('name')->all();

        $this->assertSame(['Alice Doe'], $results);
    }

    public function test_it_blocks_filtering_without_an_explicit_filter_allow_list(): void
    {
        config()->set('query-builder.strict_mode', true);

        try {
            OpenUser::QueryBuild([
                'filters' => [
                    'status' => 'active',
                ],
            ])->get();

            $this->fail('Expected strict mode to block unlisted filters.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('filters', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('allow-list', $exception->errors()[0]['reason']);
        }
    }

    public function test_it_blocks_eager_loading_without_an_explicit_relation_allow_list(): void
    {
        config()->set('query-builder.strict_mode', true);

        try {
            OpenUser::QueryBuild([
                'with' => ['profile'],
            ])->get();

            $this->fail('Expected strict mode to block unlisted eager loads.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('with', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('allow-list', $exception->errors()[0]['reason']);
        }
    }

    public function test_it_blocks_column_selection_without_an_explicit_selectable_allow_list(): void
    {
        config()->set('query-builder.strict_mode', true);

        try {
            OpenUser::QueryBuild([
                'columns' => 'name,email',
            ])->get();

            $this->fail('Expected strict mode to block unlisted selected columns.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('columns', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('[$selectable]', $exception->errors()[0]['reason']);
        }
    }

    public function test_it_blocks_requested_sorting_without_an_explicit_sort_allow_list(): void
    {
        config()->set('query-builder.strict_mode', true);

        try {
            OpenUser::QueryBuild([
                'sort_by' => 'name',
                'sort_dir' => 'desc',
            ])->get();

            $this->fail('Expected strict mode to block unlisted sort fields.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('sort_by', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('not allowed', $exception->errors()[0]['reason']);
        }
    }

    public function test_it_blocks_manual_deleted_at_filters_in_strict_mode(): void
    {
        config()->set('query-builder.strict_mode', true);

        try {
            RiskyUser::QueryBuild([
                'filters' => [
                    'deleted_at' => [
                        'operator' => 'not_null',
                    ],
                ],
            ])->get();

            $this->fail('Expected strict mode to block direct deleted_at filters.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('filters.deleted_at', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('[trashed]', $exception->errors()[0]['reason']);
        }
    }

    public function test_it_blocks_overly_deep_relation_filters_in_strict_mode(): void
    {
        config()->set('query-builder.strict_mode', true);
        config()->set('query-builder.max_relation_depth', 3);

        try {
            RiskyUser::QueryBuild([
                'filters' => [
                    'roles.permissions.roles.name' => 'Admin',
                ],
            ])->get();

            $this->fail('Expected strict mode to block overly deep relation filters.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('filters.roles.permissions.roles.name', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('maximum relation depth', $exception->errors()[0]['reason']);
        }
    }

    public function test_it_blocks_overly_deep_relation_search_fields_in_strict_mode(): void
    {
        config()->set('query-builder.strict_mode', true);
        config()->set('query-builder.max_relation_depth', 3);

        try {
            RiskyUser::QueryBuild([
                'search' => 'Admin',
            ])->get();

            $this->fail('Expected strict mode to block overly deep relation search fields.');
        } catch (InvalidQueryBuilderQuery $exception) {
            $this->assertSame('search', $exception->errors()[0]['parameter']);
            $this->assertStringContainsString('maximum relation depth', $exception->errors()[0]['reason']);
        }
    }
}
