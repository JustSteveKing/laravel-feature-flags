<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;
use JustSteveKing\Laravel\FeatureFlags\Tests\Stubs\User;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use Illuminate\Support\Facades\Hash;

class BladeDirectivesTest extends TestCase
{
    use InteractsWithViews;

    /**
     * @test
     */
    public function it_shows_feature_to_user_that_have_feature()
    {
        $user = User::create([
            'name' => 'test user',
            'email' => 'test@user.com',
            'password' => Hash::make('password')
        ]);

        $feature = Feature::create([
            'name' => 'test'
        ]);

        $user->giveFeature($feature->name);

        $view = $this->actingAs($user)->blade("@feature('test') active feature @endfeature");

        $view->assertSee('active feature');
    }

    /**
     * @test
     */
    public function it_shows_feature_to_user_in_feature_group()
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

        $view = $this->actingAs($user)->blade("@featuregroup('test group') active feature @endfeaturegroup");

        $view->assertSee('active feature');
    }

    /**
     * @test
     */
    public function it_shows_feature_to_user_when_feature_group_has_feature()
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

        $view = $this->actingAs($user)->blade("@groupfeature('test') active feature @endgroupfeature");

        $view->assertSee('active feature');
    }

    /**
     * @test
     */
    public function it_hides_feature_to_user_that_dont_have_feature()
    {
        $user = User::create([
            'name' => 'test user',
            'email' => 'test@user.com',
            'password' => Hash::make('password')
        ]);

        $view = $this->actingAs($user)->blade("@feature('test') active feature @endfeature");

        $view->assertSee('');
    }

    /**
     * @test
     */
    public function it_hides_feature_to_user_not_in_feature_group()
    {
        $user = User::create([
            'name' => 'test user',
            'email' => 'test@user.com',
            'password' => Hash::make('password')
        ]);

        $view = $this->actingAs($user)->blade("@featuregroup('test group') active feature @endfeaturegroup");

        $view->assertSee('');
    }

    /**
     * @test
     */
    public function it_hides_feature_to_user_when_feature_group_dont_have_feature()
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

        $view = $this->actingAs($user)->blade("@groupfeature('test') active feature @endgroupfeature");

        $view->assertSee('');
    }
}
