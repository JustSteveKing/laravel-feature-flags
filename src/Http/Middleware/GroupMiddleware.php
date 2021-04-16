<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GroupMiddleware
{
    public function handle(Request $request, Closure $next, string ...$groups): mixed
    {
        foreach ($groups as $group) {
            $group = str_replace($group, '-', ' ');
        }

        if ($request->user()->inGroup($groups)) {
            return $next($request);
        }

        if (config('feature-flag.middleware.mode') === 'abort') {
            return abort(config('feature-flag.middleware.status_code'));
        }

        return redirect(config('feature-flag.middleware.redirect_route'));
    }
}
