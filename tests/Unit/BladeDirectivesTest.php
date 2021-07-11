<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;
use JustSteveKing\Laravel\FeatureFlags\Tests\Stubs\User;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use Illuminate\Support\Facades\Hash;

uses(InteractsWithViews::class);

beforeEach(function (): void {
    $this->user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);
});

it('shows feature to user that have feature', function(): void {
    $feature = Feature::create([
        'name' => 'test'
    ]);

    $this->user->giveFeature($feature->name);

    $view = $this->actingAs($this->user)->blade("@feature('test') active feature @endfeature");

    $view->assertSee('active feature');
});

it('shows feature to user in feature group', function(): void {
    $group = FeatureGroup::create([
        'name' => 'Test Group',
    ]);

    $this->user->joinGroup($group->name);

    $view = $this->actingAs($this->user)->blade("@featuregroup('test group') active feature @endfeaturegroup");

    $view->assertSee('active feature');
});

it('shows feature to user when feature group has feature', function(): void {
    $feature = Feature::create([
        'name' => 'test'
    ]);

    $group = FeatureGroup::create([
        'name' => 'Test Group',
    ]);

    $group->addFeature($feature);

    $this->user->joinGroup($group->name);

    $view = $this->actingAs($this->user)->blade("@groupfeature('test') active feature @endgroupfeature");

    $view->assertSee('active feature');
});

it('hides feature to user that dont have feature', function(): void {
    $view = $this->actingAs($this->user)->blade("@feature('test') active feature @endfeature");

    $view->assertSee('');
});

it('hides feature to user that not in feature group', function(): void {
    $view = $this->actingAs($this->user)->blade("@featuregroup('test group') active feature @endfeaturegroup");

    $view->assertSee('');
});

it('hides feature to user when feature group dont have feature', function(): void {
    $group = FeatureGroup::create([
        'name' => 'Test Group',
    ]);

    $this->user->joinGroup($group->name);

    $view = $this->actingAs($this->user)->blade("@groupfeature('test') active feature @endgroupfeature");

    $view->assertSee('');
});
