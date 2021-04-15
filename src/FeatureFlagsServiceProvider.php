<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags;

use Illuminate\Support\ServiceProvider;

class FeatureFlagsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function boot()
    {
        //
    }
}
