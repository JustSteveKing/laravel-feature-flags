<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

class ActivateFeature extends Command
{
    protected $signature = 'feature-flags:activate-feature';

    protected $description = 'Activates a feature';

    public function handle(): int
    {
        $featureName = $this->ask('Feature name to activate');
        $feature = Feature::name($featureName)->first();

        while (!$feature) {
            $this->alert("There is no feature with the name '{$featureName}'");
            $featureName = $this->ask('Feature name to activate');
            $feature = Feature::name($featureName)->first();
        }

        if ($feature->active) {
            $this->info("Feature '{$featureName}' is already active.");
            return 0;
        }

        $feature->active = true;
        $feature->save();

        $this->info("Feature '{$featureName}' has been successfully activated");

        return 0;
    }
}
