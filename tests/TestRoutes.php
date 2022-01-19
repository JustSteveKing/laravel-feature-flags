<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests;

use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use JustSteveKing\Laravel\FeatureFlags\Http\Middleware\API\FeatureMiddleware as ApiFeatureMiddleware;
use JustSteveKing\Laravel\FeatureFlags\Http\Middleware\API\GroupMiddleware as ApiGroupMiddleware;
use JustSteveKing\Laravel\FeatureFlags\Http\Middleware\FeatureMiddleware;
use JustSteveKing\Laravel\FeatureFlags\Http\Middleware\GroupMiddleware;

trait TestRoutes
{
    /**
     * @param Router $router
     * @return void
     */
    protected function defineRoutes($router)
    {
        $router
            ->aliasMiddleware('feature', FeatureMiddleware::class)
            ->aliasMiddleware('feature-group', GroupMiddleware::class)
            ->aliasMiddleware('api-feature', ApiFeatureMiddleware::class)
            ->aliasMiddleware('api-feature-group', ApiGroupMiddleware::class);

        $router->get('/feature', function (): Response {
            return response('can access feature');
        })->middleware('feature:test-feature, test feature two');

        $router->get('/feature-group', function (): Response {
            return response('can access feature group');
        })->middleware('feature-group:test-feature-group, test feature two');

        $router->prefix('/api', function (Router $router): void {

            $router->get('/feature', function (): Response {
                return response('can access feature');
            })->middleware('api-feature:test-feature, test feature two');

            $router->get('/feature-group', function (): Response {
                return response('can access feature group');
            })->middleware('api-feature-group:test-feature-group, test feature two');
        });
    }
}
