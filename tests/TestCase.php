<?php

namespace GhostCompiler\LaravelQueryBuilder\Tests;

use GhostCompiler\LaravelQueryBuilder\LaravelQueryBuilderServiceProvider;
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
        $app['config']->set('database.default', 'testing');
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
            'status' => 'active',
            'is_active' => true,
            'score' => 95,
            'created_at' => '2025-01-10 10:00:00',
            'updated_at' => '2025-01-10 10:00:00',
        ]);

        $bob = User::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'status' => 'active',
            'is_active' => true,
            'score' => 72,
            'created_at' => '2025-03-12 10:00:00',
            'updated_at' => '2025-03-12 10:00:00',
        ]);

        $charlie = User::create([
            'name' => 'Charlie Ray',
            'email' => 'charlie@example.com',
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
            'bio' => 'Platform admin and API architect',
        ]);

        Profile::create([
            'user_id' => $bob->id,
            'city' => 'Berlin',
            'country' => 'DE',
            'bio' => 'Content writer and editor',
        ]);

        Profile::create([
            'user_id' => $charlie->id,
            'city' => 'Cairo',
            'country' => 'EG',
            'bio' => 'Support specialist',
        ]);

        Post::create([
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

        $charlie->delete();
    }
}
