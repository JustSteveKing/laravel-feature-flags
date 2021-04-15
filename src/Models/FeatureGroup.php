<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User;
use JustSteveKing\Laravel\FeatureFlags\Models\Concerns\NormaliseName;
use JustSteveKing\Laravel\FeatureFlags\Models\Builders\FeatureGroupBuilder;

class FeatureGroup extends Model
{
    use NormaliseName;
    
    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function addFeature(Feature $feature): array
    {
        return $this->features()->sync($feature);
    }

    public function hasFeature(string $featureName): bool
    {
        return $this->features->contains('name', $featureName);
    }

    public function removeFeature(Feature $feature): bool
    {
        return (bool) $this->features()->detach($feature->id);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_feature_group',
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'feature_group_user',
        );
    }

    public function newEloquentBuilder($query): FeatureGroupBuilder
    {
        return new FeatureGroupBuilder($query);
    }
}
