<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

uses(InteractsWithConsole::class);

it('can add a new feature', function(): void {
    $this->artisan('feature-flags:add-feature')
        ->expectsQuestion('Feature Name', 'test feature')
        ->expectsQuestion('Feature Description', 'A description')
        ->expectsChoice('Is the feature active', 'yes', ['yes', 'no'])
        ->expectsOutput("Created 'test feature' feature")
        ->assertExitCode(0);

    $this->assertCount(1, Feature::all());
});

it('can ask again if feature already exists', function(): void {
    Feature::create([
        'name' => 'test feature',
    ]);

    $this->artisan('feature-flags:add-feature')
        ->expectsQuestion('Feature Name', 'test feature')
        ->expectsQuestion('Feature Name', 'another feature')
        ->expectsQuestion('Feature Description', 'A description')
        ->expectsChoice('Is the feature active', 'yes', ['yes', 'no'])
        ->expectsOutput("Created 'another feature' feature")
        ->assertExitCode(0);

    $this->assertCount(2, Feature::all());
});

it('can activate a inactive feature', function(): void {
    Feature::create([
        'name' => 'test feature',
        'active' => false,
    ]);

    $this->artisan('feature-flags:activate-feature')
        ->expectsQuestion('Feature name to activate', 'test feature')
        ->expectsOutput("Feature 'test feature' has been successfully activated")
        ->assertExitCode(0);

    $this->assertTrue(Feature::first()->active);
});

it('can not activate a active feature', function(): void {
    Feature::create([
        'name' => 'test feature',
        'active' => true,
    ]);

    $this->artisan('feature-flags:activate-feature')
        ->expectsQuestion('Feature name to activate', 'test feature')
        ->expectsOutput("Feature 'test feature' is already active.")
        ->assertExitCode(0);

    $this->assertTrue(Feature::first()->active);
});

it('can ask again if activating feature was not found', function(): void {
    Feature::create([
        'name' => 'test feature',
        'active' => false,
    ]);

    $this->artisan('feature-flags:activate-feature')
        ->expectsQuestion('Feature name to activate', 'not found')
        ->expectsQuestion('Feature name to activate', 'test feature')
        ->expectsOutput("Feature 'test feature' has been successfully activated")
        ->assertExitCode(0);
});

it('can deactivate a active feature', function(): void {
    Feature::create([
        'name' => 'test feature',
        'active' => true,
    ]);

    $this->artisan('feature-flags:deactivate-feature')
        ->expectsQuestion('Feature name to deactivate', 'test feature')
        ->expectsOutput("Feature 'test feature' has been successfully deactivated")
        ->assertExitCode(0);

    $this->assertFalse(Feature::first()->active);
});

it('can not deactivate a inactive feature', function(): void {
    Feature::create([
        'name' => 'test feature',
        'active' => false,
    ]);

    $this->artisan('feature-flags:deactivate-feature')
        ->expectsQuestion('Feature name to deactivate', 'test feature')
        ->expectsOutput("Feature 'test feature' is already inactive.")
        ->assertExitCode(0);

    $this->assertFalse(Feature::first()->active);
});

it('can ask again if deactivating feature was not found', function(): void {
    Feature::create([
        'name' => 'test feature',
        'active' => true,
    ]);

    $this->artisan('feature-flags:deactivate-feature')
        ->expectsQuestion('Feature name to deactivate', 'not found')
        ->expectsQuestion('Feature name to deactivate', 'test feature')
        ->expectsOutput("Feature 'test feature' has been successfully deactivated")
        ->assertExitCode(0);
});

it('can display a table of empty features', function(): void {
    $this->artisan('feature-flags:view-features')
        ->expectsTable(['Name', 'Description', 'Active'], []);
});

it('can display a table of all features', function(): void {
    Feature::create([
        'name' => 'first feature',
    ]);

    Feature::create([
        'name' => 'second feature',
        'description' => 'A description'
    ]);

    Feature::create([
        'name' => 'third feature',
        'active' => false,
    ]);

    $expected_rows = Feature::all(['name', 'description', 'active'])->toArray();

    $this->artisan('feature-flags:view-features')
        ->expectsTable(['Name', 'Description', 'Active'], $expected_rows);
});
