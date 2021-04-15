<?php


declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

class AddFeature extends Command
{
    protected $signature = 'feature-flags:add-feature';

    protected $description = 'Add a new feature';

    public function handle()
    {
        $featureName = $this->ask('Feature Name');
        $description = $this->ask('Feature Description');
        $active = $this->choice('Is the feature active', ['yes', 'no'], 'no');

        $existingGroup = Feature::name($featureName)->first();

        if ($existingGroup) {
            $this->alert('A feature already exists with this name');
            return 1;
        }

        Feature::create([
            'name' => $featureName,
            'description' => $description,
            'active' => $active == 'yes',
        ]);

        $this->info("Created '{$featureName}' feature");

        return 0;
    }
}
