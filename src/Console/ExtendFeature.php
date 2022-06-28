<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

class ExtendFeature extends Command
{
    protected $signature = 'feature-flags:extend-feature';

    protected $description = 'Extend a features expiry date';

    public function handle(): int
    {
        if(! config('feature-flags.enable_time_bombs')) $this->info("Time bombs are not enabled!");

        $featureName = $this->ask('Feature Name to Extend');
        $feature = Feature::name($featureName)->first();

        $extendBy = $this->ask('When do you want your feature to expire? (Number of Days)', 0);

        $feature->expires_at = $feature->expires_at->addDays($extendBy);
        $feature->save();

        $this->info("Updated '{$featureName}' feature expiry date");

        return 0;
    }
}
