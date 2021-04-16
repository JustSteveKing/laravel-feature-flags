<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use JustSteveKing\Laravel\FeatureFlags\Console\AddFeature;
use JustSteveKing\Laravel\FeatureFlags\Console\ViewFeatures;
use JustSteveKing\Laravel\FeatureFlags\Console\ActivateFeature;
use JustSteveKing\Laravel\FeatureFlags\Console\AddFeatureGroup;
use JustSteveKing\Laravel\FeatureFlags\Console\ViewFeatureGroups;
use JustSteveKing\Laravel\FeatureFlags\Console\AddFeatureToGroup;
use JustSteveKing\Laravel\FeatureFlags\Console\DeactivateFeature;
use JustSteveKing\Laravel\FeatureFlags\Console\ViewGroupsWithFeatures;

class FeatureFlagsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/feature-flags.php' => config_path('feature-flags.php')
        ], 'config');


        Blade::directive('feature', function ($feature) {
            return "<?php if (auth()->check() && auth()->user()->hasFeature({$feature})): ?>";
        });
        Blade::directive('endfeature', function () {
            return "<?php endif; ?>";
        });


        Blade::directive('featuregroup', function ($featureGroup) {
            return "<?php if (auth()->check() && auth()->user()->inGroup({$featureGroup})): ?>";
        });
        Blade::directive('endfeaturegroup', function () {
            return "<?php endif; ?>";
        });


        Blade::directive('groupfeature', function ($feature) {
            return "<?php if (auth()->check() && auth()->user()->groupHasFeature({$feature})): ?>";
        });
        Blade::directive('endgroupfeature', function () {
            return "<?php endif; ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddFeature::class,
                ViewFeatures::class,
                ActivateFeature::class,
                AddFeatureGroup::class,
                ViewFeatureGroups::class,
                AddFeatureToGroup::class,
                DeactivateFeature::class,
                ViewGroupsWithFeatures::class,
            ]);
        }
    }
}
