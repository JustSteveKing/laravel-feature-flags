<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class AddFeatureToGroup extends Command
{
    protected $signature = 'feature-flags:add-feature-to-group';

    protected $description = 'Add a feature to a group';

    public function handle()
    {
        $featureName = $this->ask('Feature Name');
        $groupName = $this->ask('Group Name');

        $feature = Feature::name($featureName)->first();
        $group = FeatureGroup::name($groupName)->first();

        if (!$feature) {
            $this->alert("There is no feature with the name {$featureName}");
        }

        if (!$group) {
            $this->alert("There is no group with the name {$groupName}");
        }

        if (!$feature || !$group) {
            return 1;
        }

        $group->addFeature($feature);

        $this->info("Added feature '{$featureName}' to group '{$groupName}'");

        return 0;
    }
}
