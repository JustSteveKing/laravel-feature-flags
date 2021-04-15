<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function addFeature(Feature $feature)
    {
        return $this->feature()->sync($feature);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(
            Feature::class,
            'feature_feature_group',
        );
    }

    public function newEloquentBuilder($query): FeatureGroupBuilder
    {
        return new FeatureGroupBuilder($query);
    }
}
