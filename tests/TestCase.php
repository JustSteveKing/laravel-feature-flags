<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JustSteveKing\Laravel\FeatureFlags\FeatureFlagsServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            FeatureFlagsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set(
            'auth.providers.users.model',
            \JustSteveKing\Laravel\FeatureFlags\Tests\Stubs\User::class
        );
    }

    protected function setUpDatabase(): void
    {
        $this->loadLaravelMigrations();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

    }
}
