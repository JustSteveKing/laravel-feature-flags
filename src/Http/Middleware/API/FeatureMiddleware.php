<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;

class FeatureMiddleware
{
    public function handle(Request $request, Closure $next, string ...$features): mixed
    {
        foreach ($features as $feature) {
            $feature = str_replace('-', ' ', $feature);

            if (! $request->user()->hasFeature($feature)) {
                return abort(config('feature-flags.middleware.status_code'));
            }
        }

        return $next($request);
    }
}
