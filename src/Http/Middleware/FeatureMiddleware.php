<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FeatureMiddleware
{
    public function handle(Request $request, Closure $next, string ...$features): mixed
    {
        foreach ($features as $feature) {
            if (! auth()->user()->hasFeature(Str::replaceFirst('-', ' ', $feature))) {
                return abort(config('feature-flag.middleware.status_code'));
            }
        }

        return $next($request);
    }
}
