<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests\Models;

use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use JustSteveKing\Laravel\FeatureFlags\Tests\TestCase;

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
}
