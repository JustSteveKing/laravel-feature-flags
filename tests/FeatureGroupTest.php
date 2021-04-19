<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests;

use Illuminate\Support\Facades\Hash;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use JustSteveKing\Laravel\FeatureFlags\Tests\Stubs\User;
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

    /**
     * @test
     */
    public function it_assigns_a_feature_group_to_a_user()
    {
        $user = User::create([
            'name' => 'test user',
            'email' => 'test@user.com',
            'password' => Hash::make('password')
        ]);

        $group = FeatureGroup::create([
            'name' => 'Test Group',
        ]);

        $user->joinGroup($group->name);

        $this->assertTrue(
            $user->inGroup($group->name)
        );
    }

    /**
     * @test
     */
    public function it_assigns_a_feature_to_a_user_through_a_group()
    {
        $user = User::create([
            'name' => 'test user',
            'email' => 'test@user.com',
            'password' => Hash::make('password')
        ]);

        $feature = Feature::create([
            'name' => 'test'
        ]);

        $group = FeatureGroup::create([
            'name' => 'Test Group',
        ]);

        $group->addFeature($feature);

        $user->joinGroup($group->name);

        $this->assertTrue(
            $user->inGroup($group->name)
        );

        $this->assertTrue(
            $user->hasFeature($feature->name)
        );
    }
}
