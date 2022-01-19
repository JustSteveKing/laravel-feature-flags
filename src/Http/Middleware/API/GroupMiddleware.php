<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Http\Middleware\API;

use Closure;
use Illuminate\Http\Request;

class GroupMiddleware
{
    public function handle(Request $request, Closure $next, string ...$groups): mixed
    {
        foreach ($groups as $group) {
            $group = str_replace('-', ' ', $group);

            if (! $request->user()->inGroup($group)) {
                return abort(config('feature-flags.middleware.status_code'));
            }

            return $next($request);
        }
    }
}
