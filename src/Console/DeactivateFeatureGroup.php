<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class DeactivateFeatureGroup extends Command
{
    protected $signature = 'feature-flags:deactivate-feature-group';

    protected $description = 'Deactivates a feature group';

    public function handle(): int
    {
        $groupName = $this->ask('Group name to deactivate');
        $group = FeatureGroup::name($groupName)->first();

        while (!$group) {
            $this->alert("There is no group with the name '{$groupName}'");
            $groupName = $this->ask('Group name to deactivate');
            $group = FeatureGroup::name($groupName)->first();
        }

        if (!$group->active) {
            $this->info("Group '{$groupName}' is already inactive.");
            return 0;
        }

        $group->active = false;
        $group->save();

        $this->info("Group '{$groupName}' has been successfully deactivated");

        return 0;
    }
}
