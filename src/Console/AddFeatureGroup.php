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
        $description = $this->ask('Group Description');

        $existingGroup = FeatureGroup::name($groupName)->first();

        if ($existingGroup) {
            $this->alert('A group already exists with this name');
            return 1;
        }

        FeatureGroup::create([
            'name' => $groupName,
            'description' => $description,
        ]);

        $this->info("Created '{$groupName}' group");

        return 0;
    }
}
