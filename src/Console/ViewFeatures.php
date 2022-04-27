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
        $features = Feature::withoutEvents(function() {
            return Feature::all(['name', 'description', 'active', 'expires_at'])->toArray();
        });

        $headers = ['Name', 'Description', 'Active', 'Expires At'];

        $this->table($headers, $features);
    }
}
