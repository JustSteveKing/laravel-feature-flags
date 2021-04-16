<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class AddFeatureGroup extends Command
{
    protected $signature = 'feature-flags:add-feature-group';

    protected $description = 'Add a new feature group';

    public function handle()
    {
        $groupName = $this->ask('Group Name');
        $existingGroup = FeatureGroup::name($groupName)->first();

        if ($existingGroup) {
            $this->alert('A group already exists with this name');
            $groupName = $this->ask('Group Name');
            $existingGroup = FeatureGroup::name($groupName)->first();
        }

        $description = $this->ask('Group Description');
        $active = $this->choice('Is the group active', ['no', 'yes'], 'yes');

        FeatureGroup::create([
            'name' => $groupName,
            'description' => $description,
            'active' => $active == 'yes',
        ]);

        $this->info("Created '{$groupName}' group");

        return 0;
    }
}
