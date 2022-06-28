<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class ViewFeatureGroups extends Command
{
    protected $signature = 'feature-flags:view-feature-groups';

    protected $description = 'View feature groups';

    public function handle(): void
    {
        $groups = FeatureGroup::all(['name', 'description', 'active'])->toArray();

        $headers = ['Name', 'Description', 'Active'];

        $this->table($headers, $groups);
    }
}
