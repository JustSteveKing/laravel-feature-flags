<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

class DeactivateFeature extends Command
{
    protected $signature = 'feature-flags:deactivate-feature';

    protected $description = 'Deactivates a feature';

    public function handle(): int
    {
        $featureName = $this->ask('Feature name to deactivate');
        $feature = Feature::name($featureName)->first();

        while (!$feature) {
            $this->alert("There is no feature with the name '{$featureName}'");
            $featureName = $this->ask('Feature name to deactivate');
            $feature = Feature::name($featureName)->first();
        }

        if (!$feature->active) {
            $this->info("Feature '{$featureName}' is already inactive.");
            return 0;
        }

        $feature->active = false;
        $feature->save();

        $this->info("Feature '{$featureName}' has been successfully deactivated");

        return 0;
    }
}
