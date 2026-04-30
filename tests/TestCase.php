<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests;

use GhostCompiler\LaravelQueryBuilder\LaravelQueryBuilderServiceProvider;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\Comment;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\Permission;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\Post;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\Profile;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\Role;
use GhostCompiler\LaravelQueryBuilder\Tests\Fixtures\Models\User;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [LaravelQueryBuilderServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $driver = $this->environmentValue('TEST_DB_CONNECTION', 'sqlite');

        $app['config']->set('database.default', 'testing');

        if ($driver === 'pgsql') {
            $app['config']->set('database.connections.testing', [
                'driver' => 'pgsql',
                'host' => $this->environmentValue('TEST_DB_HOST', '127.0.0.1'),
                'port' => $this->environmentValue('TEST_DB_PORT', '5432'),
                'database' => $this->environmentValue('TEST_DB_DATABASE', 'laravel_querybuilder_test'),
                'username' => $this->environmentValue('TEST_DB_USERNAME', 'postgres'),
                'password' => $this->environmentValue('TEST_DB_PASSWORD', ''),
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]);

            return;
        }

        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $migrationPath = __DIR__.'/Fixtures/database/migrations';

        $this->artisan('migrate:fresh')->run();
        $this->artisan('migrate', [
            '--path' => $migrationPath,
            '--realpath' => true,
        ])->run();
        $this->seedFixtureData();
    }

    protected function seedFixtureData(): void
    {
        $admin = Role::create(['name' => 'Admin']);
        $editor = Role::create(['name' => 'Editor']);
        $viewer = Role::create(['name' => 'Viewer']);

        $manageUsers = Permission::create(['name' => 'Manage Users']);
        $publishPosts = Permission::create(['name' => 'Publish Posts']);

        $admin->permissions()->attach([$manageUsers->id, $publishPosts->id]);
        $editor->permissions()->attach([$publishPosts->id]);

        $alice = User::create([
            'name' => 'Alice Doe',
            'email' => 'alice@example.com',
            'tenant_id' => 1,
            'password' => 'hashed-secret-a',
            'status' => 'active',
            'is_active' => true,
            'score' => 95,
            'created_at' => '2025-01-10 10:00:00',
            'updated_at' => '2025-01-10 10:00:00',
        ]);

        $bob = User::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'tenant_id' => 1,
            'password' => 'hashed-secret-b',
            'status' => 'active',
            'is_active' => true,
            'score' => 72,
            'created_at' => '2025-03-12 10:00:00',
            'updated_at' => '2025-03-12 10:00:00',
        ]);

        $charlie = User::create([
            'name' => 'Charlie Ray',
            'email' => 'charlie@example.com',
            'tenant_id' => 2,
            'password' => 'hashed-secret-c',
            'status' => 'inactive',
            'is_active' => false,
            'score' => 40,
            'created_at' => '2025-05-01 10:00:00',
            'updated_at' => '2025-05-01 10:00:00',
        ]);

        $alice->roles()->attach([$admin->id]);
        $bob->roles()->attach([$editor->id]);
        $charlie->roles()->attach([$viewer->id]);

        Profile::create([
            'user_id' => $alice->id,
            'city' => 'Amsterdam',
            'country' => 'NL',
            'is_public' => true,
            'bio' => 'Platform admin and API architect',
        ]);

        Profile::create([
            'user_id' => $bob->id,
            'city' => 'Berlin',
            'country' => 'DE',
            'is_public' => false,
            'bio' => 'Content writer and editor',
        ]);

        Profile::create([
            'user_id' => $charlie->id,
            'city' => 'Cairo',
            'country' => 'EG',
            'is_public' => false,
            'bio' => 'Support specialist',
        ]);

        $alicePost = Post::create([
            'user_id' => $alice->id,
            'title' => 'Scaling admin workflows',
            'created_at' => '2025-01-11 08:00:00',
            'updated_at' => '2025-01-11 08:00:00',
        ]);

        Post::create([
            'user_id' => $bob->id,
            'title' => 'Editorial planning guide',
            'created_at' => '2025-03-13 08:00:00',
            'updated_at' => '2025-03-13 08:00:00',
        ]);

        Comment::create([
            'commentable_type' => User::class,
            'commentable_id' => $alice->id,
            'body' => 'Moderation notes for the admin account',
            'is_visible' => true,
            'created_at' => '2025-01-12 08:00:00',
            'updated_at' => '2025-01-12 08:00:00',
        ]);

        Comment::create([
            'commentable_type' => User::class,
            'commentable_id' => $bob->id,
            'body' => 'Internal review draft',
            'is_visible' => false,
            'created_at' => '2025-03-14 08:00:00',
            'updated_at' => '2025-03-14 08:00:00',
        ]);

        Comment::create([
            'commentable_type' => Post::class,
            'commentable_id' => $alicePost->id,
            'body' => 'Post-level comment for morph coverage',
            'is_visible' => true,
            'created_at' => '2025-01-15 08:00:00',
            'updated_at' => '2025-01-15 08:00:00',
        ]);

        $charlie->delete();
    }

    protected function environmentValue(string $key, string $default): string
    {
        $value = $_SERVER[$key] ?? $_ENV[$key] ?? getenv($key);

        if ($value === false || $value === '') {
            return $default;
        }

        return (string) $value;
    }
}
