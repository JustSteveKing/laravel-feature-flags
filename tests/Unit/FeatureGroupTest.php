<?php

declare(strict_types=1);

use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;
use JustSteveKing\Laravel\FeatureFlags\Tests\Stubs\User;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use Illuminate\Support\Facades\Hash;

beforeEach(function(): void {
    $this->group = FeatureGroup::create([
        'name' => 'Test Feature Group',
    ]);
});

it('can create a new feature group', function(): void {
    expect(FeatureGroup::all())
        ->toHaveCount(count: 1);
});

it('can normalises the group name', function(): void {
    expect(FeatureGroup::first())
        ->name->toEqual('test feature group');
});

it('can deactivate a feature group', function(): void {
    expect(FeatureGroup::active()->get())
        ->toHaveCount(count: 1);

    $this->group->update([
        'active' => false,
    ]);

    expect(FeatureGroup::inactive()->get())
        ->toHaveCount(count: 1)
        ->and(FeatureGroup::active()->get())
        ->toHaveCount(count: 0);
});

it('assigns a feature group to a user', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    expect($user)
        ->inGroup($this->group->name)
        ->toBeFalse();

    $user->joinGroup($this->group->name);

    expect($user)
        ->inGroup($this->group->name)
        ->toBeTrue();
});

it('assigns a feature to a user through a group', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $feature = Feature::create([
        'name' => 'Test Feature'
    ]);

    $this->group->addFeature($feature);

    $user->joinGroup($this->group->name);

    expect($user)
        ->inGroup($this->group->name)
        ->toBeTrue()
        ->hasFeature($feature->name)
        ->toBeTrue();
});

it('checks a group has a feature', function(): void {
    $feature = Feature::create([
        'name' => 'Test Feature'
    ]);

    $this->group->addFeature($feature);

    expect($this->group)
        ->hasFeature($feature->name)
        ->toBeTrue();
});
