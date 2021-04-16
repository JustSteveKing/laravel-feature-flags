<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class ActivateFeatureGroup extends Command
{
    protected $signature = 'feature-flags:activate-feature-group';

    protected $description = 'Activates a feature group';

    public function handle()
    {
        $groupName = $this->ask('Group name to activate');
        $group = FeatureGroup::name($groupName)->first();

        while (!$group) {
            $this->alert("There is no group with the name '{$groupName}'");
            $groupName = $this->ask('Group name to activate');
            $group = FeatureGroup::name($groupName)->first();
        }

        if ($group->active) {
            $this->info("Group '{$groupName}' is already active.");
            return 0;
        }

        $group->active = true;
        $group->save();

        $this->info("Group '{$groupName}' has been successfully activated");

        return 0;
    }
}
