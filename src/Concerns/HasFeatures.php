<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use JustSteveKing\Laravel\FeatureFlags\Models\FeatureGroup;

trait HasFeatures
{
    public function giveFeature(...$features): self
    {
        $features = $this->getAllFeatures(
            features: Arr::flatten($features)
        );

        if (is_null($features)) {
            return $this;
        }

        $this->features()->saveMany($features);

        return $this;
    }

    public function removeFeature(...$features): self
    {
        $features = $this->getAllFeatures(
            features: Arr::flatten($features)
        );

        $this->features()->detach($features);

        return $this;
    }

    public function updateFeatures(...$features): self
    {
        $this->features()->detach();

        return $this->giveFeature($features);
    }

    public function hasFeature(string $feature): bool
    {
        return $this->hasFeatureThroughGroup(
            feature: $feature,
        ) || $this->hasFeatureDirect(
            feature: $feature,
        );
    }

    public function hasFeatureDirect(string $feature): bool
    {
        $feature = Feature::active()->name($feature)->first();

        if (is_null($feature)) {
            return false;
        }

        return $this->features->contains($feature);
    }

    public function hasFeatureThroughGroup(string $feature): bool
    {
        $feature = Feature::with(['groups'])->active()
            ->name($feature)->first();

        if (is_null($feature)) {
            return false;
        }

        foreach ($feature->groups as $group) {
            if ($this->groups->contains($group)) {
                return true;
            }
        }

        return false;
    }

    public function inGroup(...$groups): bool
    {
        foreach ($groups as $group)
        {
            $group = strtolower($group);
        }

        return $this
            ->groups()
            ->active()
            ->whereIn('name', $groups)
            ->count() > 0;
    }

    public function leaveGroup(...$groups): self
    {
        $groups = $this->getAllGroups(
            groups: Arr::flatten($groups)
        );

        $this->groups()->detach($groups);

        return $this;
    }

    public function joinGroup(...$groups): self
    {
        $groups = $this->getAllGroups(
            groups: Arr::flatten($groups)
        );

        if (is_null($groups)) {
            return $this;
        }

        $this->groups()->saveMany($groups);

        return $this;
    }

    public function addToGroup(...$groups): self
    {
        return $this->joinGroup(
            groups: $groups,
        );
    }

    protected function getAllFeatures(array $features): Collection
    {
        foreach ($features as $feature) {
            $feature = strtolower($feature);
        }

        return Feature::active()->whereIn('name', $features)->get();
    }

    protected function getAllGroups(array $groups): Collection
    {
        foreach ($groups as $group) {
            $group = strtolower($group);
        }

        return FeatureGroup::active()->whereIn('name', $groups)->get();
    }

    public function groupHasFeature(string $featureName): bool
    {
        return $this->hasFeatureThroughGroup(
            feature: $featureName,
        );
    }

    protected function featureExists(string $featureName): bool
    {
        $exists = Feature::name($featureName)->first();

        return !is_null($exists);
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
