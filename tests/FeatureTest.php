<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests;

use Illuminate\Foundation\Auth\User;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class FeatureTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_create_a_new_feature()
    {
        Feature::create([
            'name' => 'test feature',
        ]);

        $this->assertCount(1, Feature::all());
    }

    /**
     * @test
     */
    public function it_normalises_the_name()
    {
        Feature::create([
            'name' => 'Test Feature',
        ]);

        $this->assertEquals(
            'test feature',
            Feature::first()->name
        );
    }

    /**
     * @test
     */
    public function it_will_deactivate_a_feature()
    {
        Feature::create([
            'name' => 'test feature',
        ]);

        $this->assertCount(1, Feature::active()->get());

        Feature::first()->update([
            'active' => false
        ]);

        $this->assertCount(1, Feature::inactive()->get());
        $this->assertCount(0, Feature::active()->get());
    }

    /**
     * @test
     */
    public function it_can_join_a_group()
    {
        FeatureGroup::create([
            'name' => 'group 1'
        ]);

        Feature::create([
            'name' => 'feature 1'
        ]);

        $this->assertCount(1, Feature::all());
        $this->assertCount(1, FeatureGroup::all());

        $feature = Feature::first();
        $feature->groups()->attach(FeatureGroup::find(1));
        $this->assertCount(1, $feature->groups);
        $this->assertTrue($feature->inGroup(FeatureGroup::first()->name));
    }
}
