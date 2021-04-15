<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FeatureFlagsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('feature-flags')
            ->hasConfigFile()
            ->hasCommands()
            ->hasMigrations();
    }
}
