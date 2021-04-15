<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags;

use Illuminate\Support\Facades\Facade;

class FeatureFlagsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'feature-flags';
    }
}
