<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class GroupMiddleware
{
    public function handle(Request $request, Closure $next, string ...$groups): mixed
    {
        foreach ($groups as $group) {
            if (! auth()->user()->inGroup(Str::replaceFirst('-', ' ', $group))) {
                return abort(403);
            }
        }

        return $next($request);
    }
}
