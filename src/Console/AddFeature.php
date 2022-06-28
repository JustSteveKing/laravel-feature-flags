<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

class AddFeature extends Command
{
    protected $signature = 'feature-flags:add-feature';

    protected $description = 'Add a new feature';

    public function handle(): int
    {
        $featureName = $this->ask('Feature Name');
        $existingFeature = Feature::name($featureName)->first();

        while ($existingFeature) {
            $this->alert('A feature already exists with this name');
            $featureName = $this->ask('Feature Name');
            $existingFeature = Feature::name($featureName)->first();
        }

        $description = $this->ask('Feature Description');
        $active = $this->choice('Is the feature active', ['no', 'yes'], 'yes');

        if(config('feature-flags.enable_time_bombs')) {
            $expires_at = $this->ask('When do you want your feature to expire? (Number of Days)', 0);
        }

        Feature::create([
            'name' => $featureName,
            'description' => $description,
            'active' => $active == 'yes',
            'expires_at' => isset($expires_at) ? \Carbon\Carbon::now()->addDays($expires_at) : null
        ]);

        $this->info("Created '{$featureName}' feature");

        return 0;
    }
}
