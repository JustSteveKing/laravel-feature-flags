<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

uses(InteractsWithConsole::class);

it('can create a new feature group', function(): void {
    $this->artisan('feature-flags:add-feature-group')
        ->expectsQuestion('Group Name', 'test group')
        ->expectsQuestion('Group Description', 'A group description')
        ->expectsChoice('Is the group active', 'yes', ['no', 'yes'])
        ->expectsOutput("Created 'test group' group")
        ->assertExitCode(0);

    expect(FeatureGroup::all())
        ->toHaveCount(count: 1);
});

it('can ask again if feature group already exists', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
    ]);

    $this->artisan('feature-flags:add-feature-group')
        ->expectsQuestion('Group Name', 'test group')
        ->expectsQuestion('Group Name', 'another group')
        ->expectsQuestion('Group Description', 'A group description')
        ->expectsChoice('Is the group active', 'yes', ['no', 'yes'])
        ->expectsOutput("Created 'another group' group")
        ->assertExitCode(0);

    expect(FeatureGroup::all())
        ->toHaveCount(count: 2);
});

it('can activate an inactive feature group', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
        'active' => false,
    ]);

    $this->artisan('feature-flags:activate-feature-group')
        ->expectsQuestion('Group name to activate', 'test group')
        ->expectsOutput("Group 'test group' has been successfully activated")
        ->assertExitCode(0);

    expect(FeatureGroup::first())
        ->active->toBeTrue();
});

it('cannot activate an active feature group', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
        'active' => true,
    ]);

    $this->artisan('feature-flags:activate-feature-group')
        ->expectsQuestion('Group name to activate', 'test group')
        ->expectsOutput("Group 'test group' is already active.")
        ->assertExitCode(0);

    expect(FeatureGroup::first())
        ->active->toBeTrue();
});

it('can ask again if activating feature group was not found', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
        'active' => false,
    ]);

    $this->artisan('feature-flags:activate-feature-group')
        ->expectsQuestion('Group name to activate', 'not found')
        ->expectsQuestion('Group name to activate', 'test group')
        ->expectsOutput("Group 'test group' has been successfully activated")
        ->assertExitCode(0);

    expect(FeatureGroup::first())
        ->active->toBeTrue();
});

it('can deactivate an active feature group', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
        'active' => true,
    ]);

    $this->artisan('feature-flags:deactivate-feature-group')
        ->expectsQuestion('Group name to deactivate', 'test group')
        ->expectsOutput("Group 'test group' has been successfully deactivated")
        ->assertExitCode(0);

    expect(FeatureGroup::first())
        ->active->toBeFalse();
});

it('can not deactivate an inactive feature group', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
        'active' => false,
    ]);

    $this->artisan('feature-flags:deactivate-feature-group')
        ->expectsQuestion('Group name to deactivate', 'test group')
        ->expectsOutput("Group 'test group' is already inactive.")
        ->assertExitCode(0);

    expect(FeatureGroup::first())
        ->active->toBeFalse();
});

it('can ask again if deactivating feature group was not found', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
        'active' => true,
    ]);

    $this->artisan('feature-flags:deactivate-feature-group')
        ->expectsQuestion('Group name to deactivate', 'not found')
        ->expectsQuestion('Group name to deactivate', 'test group')
        ->expectsOutput("Group 'test group' has been successfully deactivated")
        ->assertExitCode(0);

        expect(FeatureGroup::first())
            ->active->toBeFalse();
});

it('can display a table of empty feature groups', function(): void {
    $this->artisan('feature-flags:view-feature-groups')
        ->expectsTable(['Name', 'Description', 'Active'], []);
});

it('can display a table of all feature groups', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
    ]);

    FeatureGroup::create([
        'name' => 'test group',
        'active' => 'false',
    ]);

    FeatureGroup::create([
        'name' => 'test group',
        'description' => 'A group description',
    ]);

    $expected_rows = FeatureGroup::all(['name', 'description', 'active'])->toArray();

    $this->artisan('feature-flags:view-feature-groups')
        ->expectsTable(['Name', 'Description', 'Active'], $expected_rows);
});

it('can add a feature to a feature group', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
    ]);

    Feature::create([
        'name' => 'test feature',
    ]);

    $this->artisan('feature-flags:add-feature-to-group')
        ->expectsQuestion('Feature Name', 'test feature')
        ->expectsQuestion('Group Name', 'test group')
        ->expectsOutput("Added feature 'test feature' to group 'test group'")
        ->assertExitCode(0);
});

it('can ask for feature again if feature to add didnt exist', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
    ]);

    Feature::create([
        'name' => 'test feature',
    ]);

    $this->artisan('feature-flags:add-feature-to-group')
        ->expectsQuestion('Feature Name', 'another feature')
        ->expectsQuestion('Feature Name', 'test feature')
        ->expectsQuestion('Group Name', 'test group')
        ->expectsOutput("Added feature 'test feature' to group 'test group'")
        ->assertExitCode(0);
});

it('can ask for feature group if group to add feature didnt exist', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
    ]);

    Feature::create([
        'name' => 'test feature',
    ]);

    $this->artisan('feature-flags:add-feature-to-group')
        ->expectsQuestion('Feature Name', 'test feature')
        ->expectsQuestion('Group Name', 'another group')
        ->expectsQuestion('Group Name', 'test group')
        ->expectsOutput("Added feature 'test feature' to group 'test group'")
        ->assertExitCode(0);
});

it('can alert if feature is already assigned to feature group', function(): void {
    $group = FeatureGroup::create([
        'name' => 'test group',
    ]);

    $feature = Feature::create([
        'name' => 'test feature',
    ]);

    $group->addFeature($feature);

    $this->artisan('feature-flags:add-feature-to-group')
        ->expectsQuestion('Feature Name', 'test feature')
        ->expectsQuestion('Group Name', 'test group')
        ->expectsOutput('*     This feature is already assigned to this group     *')
        ->assertExitCode(1);
});

it('can display an empty table of feature group with features', function(): void {
    $this->artisan('feature-flags:view-groups-with-features')
        ->expectsTable(['Group', 'Features'], []);
});

it('can display a table with feature group with not features', function(): void {
    FeatureGroup::create([
        'name' => 'test group',
    ]);

    $this->artisan('feature-flags:view-groups-with-features')
        ->expectsTable(['Group', 'Features'], [['test group'], []]);
});

it('can display a table with feature group with one feature', function(): void {
    $group = FeatureGroup::create([
        'name' => 'test group',
    ]);

    $feature = Feature::create([
        'name' => 'test feature',
    ]);

    $group->addFeature($feature);

    $this->artisan('feature-flags:view-groups-with-features')
        ->expectsTable(['Group', 'Features'], [['test group', 'test feature']]);
});

it('can display a table with feature group with nth features', function(): void {
    $group = FeatureGroup::create([
        'name' => 'test group',
    ]);

    $feature = Feature::create([
        'name' => 'test feature',
    ]);

    $featureTwo = Feature::create([
        'name' => 'another feature',
    ]);

    $group->addFeature($feature);
    $group->addFeature($featureTwo);

    $this->artisan('feature-flags:view-groups-with-features')
        ->expectsTable(['Group', 'Features'], [['test group', 'test feature, another feature']]);
});
