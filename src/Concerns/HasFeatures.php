<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

trait HasFeatures
{
    public function leaveGroup(string $groupName)
    {
        return $this->groups()->detach(FeatureGroup::name($groupName)->first()->id);
    }

    public function addToGroup(string $groupName)
    {
        return $this->groups()->sync(FeatureGroup::firstOrCreate([
            'name' => $groupName
        ]));
    }

    public function inGroup(string $groupName)
    {
        return $this->groups->contains('name', $groupName);
    }

    public function giveFeature(string $featureName)
    {
        return $this->features()->sync(Feature::firstOrCreate([
            'name' => $featureName
        ]));
    }

    public function hasFeature(string $featureName)
    {
        return $this->features->contains('name', $featureName);
    }

    public function removeFeature(string $featureName)
    {
        return $this->features()->detach(Feature::first([
            'name' => strtolower($featureName)
        ]));
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_user'
        );
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            FeatureGroup::class,
            'feature_group_user',
        );
    }
}
