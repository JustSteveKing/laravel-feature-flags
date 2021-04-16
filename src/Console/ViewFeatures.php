<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Console;

use Illuminate\Console\Command;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

class ViewFeatures extends Command
{
    protected $signature = 'feature-flags:view-features';

    protected $description = 'View features';

    public function handle()
    {
        $features = Feature::all(['name', 'description', 'active'])->toArray();

        $headers = ['Name', 'Description', 'Active'];

        $this->table($headers, $features);
    }
}
