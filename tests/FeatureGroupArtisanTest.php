<?php

namespace JustSteveKing\Laravel\FeatureFlags\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class FeatureGroupArtisanTest extends TestCase
{
    use InteractsWithConsole;

    /**
     * @test
    */
    public function it_can_create_a_new_feature_group()
    {
        $this->artisan('feature-flags:add-feature-group')
            ->expectsQuestion('Group Name', 'test group')
            ->expectsQuestion('Group Description', 'A group description')
            ->expectsChoice('Is the group active', 'yes', ['no', 'yes'])
            ->expectsOutput("Created 'test group' group")
            ->assertExitCode(0);
    }
    /**
     * @test
     */
    public function it_can_ask_again_if_feature_group_already_exists()
    {
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

        $this->assertCount(2, FeatureGroup::all());
    }

    /**
     * @test
     */
    public function it_can_activate_a_inactive_feature_group()
    {
        FeatureGroup::create([
            'name' => 'test group',
            'active' => false,
        ]);

        $this->artisan('feature-flags:activate-feature-group')
            ->expectsQuestion('Group name to activate', 'test group')
            ->expectsOutput("Group 'test group' has been successfully activated")
            ->assertExitCode(0);

        $this->assertTrue(FeatureGroup::first()->active);
    }

    /**
     * @test
     */
    public function it_can_not_activate_a_active_feature_group()
    {
        FeatureGroup::create([
            'name' => 'test group',
            'active' => true,
        ]);

        $this->artisan('feature-flags:activate-feature-group')
            ->expectsQuestion('Group name to activate', 'test group')
            ->expectsOutput("Group 'test group' is already active.")
            ->assertExitCode(0);

        $this->assertTrue(FeatureGroup::first()->active);
    }

    /**
     * @test
    */
    public function it_can_ask_again_if_activating_feature_group_was_not_found()
    {
        FeatureGroup::create([
            'name' => 'test group',
            'active' => false,
        ]);

        $this->artisan('feature-flags:activate-feature-group')
            ->expectsQuestion('Group name to activate', 'not found')
            ->expectsQuestion('Group name to activate', 'test group')
            ->expectsOutput("Group 'test group' has been successfully activated")
            ->assertExitCode(0);
    }

    /**
     * @test
    */
    public function it_can_deactivate_a_active_feature_group()
    {
        FeatureGroup::create([
            'name' => 'test group',
            'active' => true,
        ]);

        $this->artisan('feature-flags:deactivate-feature-group')
            ->expectsQuestion('Group name to deactivate', 'test group')
            ->expectsOutput("Group 'test group' has been successfully deactivated")
            ->assertExitCode(0);

        $this->assertFalse(FeatureGroup::first()->active);
    }

    /**
     * @test
    */
    public function it_can_not_deactivate_a_inactive_feature_group()
    {
        FeatureGroup::create([
            'name' => 'test group',
            'active' => false,
        ]);

        $this->artisan('feature-flags:deactivate-feature-group')
            ->expectsQuestion('Group name to deactivate', 'test group')
            ->expectsOutput("Group 'test group' is already inactive.")
            ->assertExitCode(0);

        $this->assertFalse(FeatureGroup::first()->active);
    }

    /**
     * @test
    */
    public function it_can_ask_again_if_deactivating_feature_group_was_not_found()
    {
        FeatureGroup::create([
            'name' => 'test group',
            'active' => true,
        ]);

        $this->artisan('feature-flags:deactivate-feature-group')
            ->expectsQuestion('Group name to deactivate', 'not found')
            ->expectsQuestion('Group name to deactivate', 'test group')
            ->expectsOutput("Group 'test group' has been successfully deactivated")
            ->assertExitCode(0);

        $this->assertFalse(FeatureGroup::first()->active);
    }

    /**
     * @test
    */
    public function it_can_display_a_table_of_empty_feature_groups()
    {
        $this->artisan('feature-flags:view-feature-groups')
            ->expectsTable(['Name', 'Description', 'Active'], []);
    }

    /**
     * @test
    */
    public function it_can_display_a_table_of_all_feature_groups()
    {
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
    }

    /**
     * @test
    */
    public function it_can_add_a_feature_to_a_feature_group()
    {
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
    }

    /**
     * @test
    */
    public function it_can_ask_for_feature_again_if_feature_to_add_didnt_exist()
    {
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
    }

    /**
     * @test
    */
    public function it_can_ask_for_feature_group_if_group_to_add_feature_didnt_exist()
    {
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
    }

    /**
     * @test
    */
    public function it_can_alert_if_feature_is_already_assigned_to_feature_group()
    {
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
    }

    /**
     * @test
    */
    public function it_can_display_an_empty_table_of_feature_group_with_features()
    {
        $this->artisan('feature-flags:view-groups-with-features')
            ->expectsTable(['Group', 'Features'], []);
    }

    /**
     * @test
    */
    public function it_can_display_a_table_with_feature_group_with_not_features()
    {
        FeatureGroup::create([
            'name' => 'test group',
        ]);

        $this->artisan('feature-flags:view-groups-with-features')
            ->expectsTable(['Group', 'Features'], [['test group'], []]);
    }

    /**
     * @test
    */
    public function it_can_display_a_table_with_feature_group_with_one_feature()
    {
        $group = FeatureGroup::create([
            'name' => 'test group',
        ]);

        $feature = Feature::create([
            'name' => 'test feature',
        ]);

        $group->addFeature($feature);

        $this->artisan('feature-flags:view-groups-with-features')
            ->expectsTable(['Group', 'Features'], [['test group', 'test feature']]);
    }

    /**
     * @test
    */
    public function it_can_display_a_table_with_feature_group_with_nth_features()
    {
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
    }
}
