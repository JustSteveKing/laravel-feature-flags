<?php

namespace JustSteveKing\Laravel\FeatureFlags\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;

class FeatureArtisanTest extends TestCase
{
    use InteractsWithConsole;

    /**
     * @test
     */
    public function it_can_add_a_new_feature()
    {
        $this->artisan('feature-flags:add-feature')
            ->expectsQuestion('Feature Name', 'test feature')
            ->expectsQuestion('Feature Description', 'A description')
            ->expectsChoice('Is the feature active', 'yes', ['yes', 'no'])
            ->expectsOutput("Created 'test feature' feature")
            ->assertExitCode(0);

        $this->assertCount(1, Feature::all());
    }

    /**
     * @test
     */
    public function it_can_ask_again_if_feature_already_exists()
    {
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
    }

    /**
     * @test
     */
    public function it_can_activate_a_inactive_feature()
    {
        Feature::create([
            'name' => 'test feature',
            'active' => false,
        ]);

        $this->artisan('feature-flags:activate-feature')
            ->expectsQuestion('Feature name to activate', 'test feature')
            ->expectsOutput("Feature 'test feature' has been successfully activated")
            ->assertExitCode(0);

        $this->assertTrue(Feature::first()->active);
    }

    /**
     * @test
     */
    public function it_can_not_activate_a_active_feature()
    {
        Feature::create([
            'name' => 'test feature',
            'active' => true,
        ]);

        $this->artisan('feature-flags:activate-feature')
            ->expectsQuestion('Feature name to activate', 'test feature')
            ->expectsOutput("Feature 'test feature' is already active.")
            ->assertExitCode(0);

        $this->assertTrue(Feature::first()->active);
    }

    /**
     * @test
     */
    public function it_can_ask_again_if_activating_feature_was_not_found()
    {
        Feature::create([
            'name' => 'test feature',
            'active' => false,
        ]);

        $this->artisan('feature-flags:activate-feature')
            ->expectsQuestion('Feature name to activate', 'not found')
            ->expectsQuestion('Feature name to activate', 'test feature')
            ->expectsOutput("Feature 'test feature' has been successfully activated")
            ->assertExitCode(0);
    }

    /**
     * @test
     */
    public function it_can_deactivate_a_active_feature()
    {
        Feature::create([
            'name' => 'test feature',
            'active' => true,
        ]);

        $this->artisan('feature-flags:deactivate-feature')
            ->expectsQuestion('Feature name to deactivate', 'test feature')
            ->expectsOutput("Feature 'test feature' has been successfully deactivated")
            ->assertExitCode(0);

        $this->assertFalse(Feature::first()->active);
    }

    /**
     * @test
    */
    public function it_can_not_deactivate_a_inactive_feature()
    {
        Feature::create([
            'name' => 'test feature',
            'active' => false,
        ]);

        $this->artisan('feature-flags:deactivate-feature')
            ->expectsQuestion('Feature name to deactivate', 'test feature')
            ->expectsOutput("Feature 'test feature' is already inactive.")
            ->assertExitCode(0);

        $this->assertFalse(Feature::first()->active);
    }

    /**
     * @test
    */
    public function it_can_ask_again_if_deactivating_feature_was_not_found()
    {
        Feature::create([
            'name' => 'test feature',
            'active' => true,
        ]);

        $this->artisan('feature-flags:deactivate-feature')
            ->expectsQuestion('Feature name to deactivate', 'not found')
            ->expectsQuestion('Feature name to deactivate', 'test feature')
            ->expectsOutput("Feature 'test feature' has been successfully deactivated")
            ->assertExitCode(0);
    }

    /**
     * @test
    */
    public function it_can_display_a_table_of_empty_features()
    {
        $this->artisan('feature-flags:view-features')
            ->expectsTable(['Name', 'Description', 'Active'], []);
    }

    /**
     * @test
    */
    public function it_can_display_a_table_of_all_features()
    {
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
    }
}
