<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Hash;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;
use JustSteveKing\Laravel\FeatureFlags\Tests\TestRoutes;
use JustSteveKing\Laravel\FeatureFlags\Tests\Stubs\User;

use function Pest\Laravel\actingAs;

uses(TestRoutes::class);

beforeEach(function (): void {
    $this->user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password'),
    ]);

    $this->app['config']->set('feature-flags.middleware', [
        'mode' => 'abort',
        'redirect_route' => '/',
        'status_code' => 404,
    ],);
});

it('can access feature page via middleware', function (): void {
    $feature = Feature::create([
        'name' => 'test feature',
    ]);

    $this->user->giveFeature($feature->name);

    actingAs($this->user)
        ->get('/api/feature')
        ->assertStatus(200);
});

it('can access feature group page via middleware', function (): void {
    $group = FeatureGroup::create([
        'name' => 'test feature group',
    ]);

    $this->user->joinGroup($group->name);

    actingAs($this->user)
        ->get('/api/feature-group')
        ->assertStatus(200);
});

it('cannot access to feature page and aborts', function (): void {
    actingAs($this->user)
        ->get('/feature')
        ->assertStatus(404);
});

it('cannot access to feature group page and aborts', function (): void {
    actingAs($this->user)
        ->get('/feature-group')
        ->assertStatus(404);
});
