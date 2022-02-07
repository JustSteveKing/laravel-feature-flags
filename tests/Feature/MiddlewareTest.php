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
        'mode' => 'redirect',
        'redirect_route' => '/',
        'status_code' => 404,
    ],);
});

it('can access feature page via middleware', function (): void {
    $feature = Feature::create([
        'name' => 'test feature',
    ]);

    $feature_two = Feature::create([
        'name' => 'test feature two',
    ]);

    $this->user->giveFeature($feature->name);
    $this->user->giveFeature($feature_two->name);

    actingAs($this->user)
        ->get('/feature')
        ->assertSeeText('can access feature')
        ->assertStatus(200);
});

it('cannot access feature page when feature flag is not attached', function (): void {
    $feature = Feature::create([
        'name' => 'test feature',
    ]);

    $this->user->giveFeature($feature->name);

    actingAs($this->user)
        ->get('/feature')
        ->assertRedirect('/');
});

it('can access feature group page via middleware', function (): void {
    $group = FeatureGroup::create([
        'name' => 'test feature group two',
    ]);

    $this->user->joinGroup($group->name);

    actingAs($this->user)
        ->get('/feature-group')
        ->assertSeeText('can access feature group')
        ->assertStatus(200);
});

it('cannot access to feature page and redirects', function (): void {
    actingAs($this->user)
        ->get('/feature')
        ->assertRedirect('/');
});

it('cannot access to feature group page and redirects', function (): void {
    actingAs($this->user)
        ->get('/feature-group')
        ->assertRedirect('/');
});

it('cannot access to feature page and aborts', function (): void {
    $this->app['config']->set('feature-flags.middleware.mode', 'abort');

    actingAs($this->user)
        ->get('/feature')
        ->assertStatus(404);
});

it('cannot access to feature group page and aborts', function (): void {
    $this->app['config']->set('feature-flags.middleware.mode', 'abort');

    actingAs($this->user)
        ->get('/feature-group')
        ->assertStatus(404);
});
