<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class ViewGroupsWithFeatures extends Command
{
    protected $signature = 'feature-flags:view-groups-with-features';

    protected $description = 'View groups with features';

    public function handle(): void
    {
        $groups = FeatureGroup::all();
        $headers = ['Group', 'Features'];
        $table = [];

        foreach ($groups as $group) {
            $features = '';
            foreach ($group->features as $feature) {
                $features .= "{$feature->name}, ";
            }

            array_push($table, [$group->name, rtrim($features, ", ")]);
        }

        $this->table($headers, $table);
    }
}
