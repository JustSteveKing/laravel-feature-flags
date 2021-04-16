<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests;

use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

class FeatureGroupTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_create_a_new_feature_group()
    {
        FeatureGroup::create([
            'name' => 'test feature group',
        ]);

        $this->assertCount(1, FeatureGroup::all());
    }

    /**
     * @test
     */
    public function it_normalises_the_name()
    {
        FeatureGroup::create([
            'name' => 'Test Feature GROUP',
        ]);

        $this->assertEquals(
            'test feature group',
            FeatureGroup::first()->name
        );
    }

    /**
     * @test
     */
    public function it_will_deactivate_a_feature_group()
    {
        FeatureGroup::create([
            'name' => 'test feature group',
        ]);

        $this->assertCount(1, FeatureGroup::active()->get());

        FeatureGroup::first()->update([
            'active' => false
        ]);

        $this->assertCount(1, FeatureGroup::inactive()->get());
        $this->assertCount(0, FeatureGroup::active()->get());
    }
}
