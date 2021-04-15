<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FeatureMiddleware
{
    public function handle(Request $request, Closure $next, string $feature): mixed
    {
        dd($feature);
        return $next($request);
    }
}
