<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FeatureMiddleware
{
    public function handle(Request $request, Closure $next, string ...$features): mixed
    {
        foreach ($features as $feature) {
            $feature = str_replace($feature, '-', ' ');
        }

        if (! $request->user()->hasFeature($features)) {
            if (config('feature-flag.middleware.mode') === 'abort') {
                return abort(config('feature-flag.middleware.status_code'));
            }

            return redirect(config('feature-flag.middleware.redirect_route'));
        }

        return $next($request);
    }
}
