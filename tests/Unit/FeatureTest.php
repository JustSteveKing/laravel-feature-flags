<?php

declare(strict_types=1);

use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;
use JustSteveKing\Laravel\FeatureFlags\Tests\Stubs\User;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use Illuminate\Support\Facades\Hash;

beforeEach(function(): void {
    $this->feature = Feature::create([
        'name' => 'test feature',
    ]);
});

it('will create a new feature', function(): void {
    expect(Feature::all())
        ->toHaveCount(count: 1);
});

it('normalises the name', function(): void {
    expect(Feature::first())
        ->name->toEqual('test feature');
});

it('will deactivate a feature', function(): void {
    expect(Feature::active()->get())
        ->toHaveCount(count: 1);

    $this->feature->update([
        'active' => false
    ]);

    expect(Feature::inactive()->get())
        ->toHaveCount(count: 1)
        ->and(Feature::active()->get())
        ->toHaveCount(count: 0);
});

it('can join a group', function(): void {
    $group = FeatureGroup::create([
        'name' => 'feature group'
    ]);

    $this->feature
        ->groups()
        ->attach($group);

    expect($this->feature)
        ->groups
        ->toHaveCount(count: 1)
        ->inGroup($group->name)
        ->toBeTrue();
});

it('assigns a feature to a user', function(): void {
    $user = User::create([
        'name' => 'test user',
        'email' => 'test@user.com',
        'password' => Hash::make('password')
    ]);

    $user->giveFeature($this->feature->name);

    expect($user)
        ->hasFeature($this->feature->name)
        ->toBeTrue();
});
