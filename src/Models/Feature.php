<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use JustSteveKing\Laravel\FeatureFlags\Models\Concerns\NormaliseName;
use JustSteveKing\Laravel\FeatureFlags\Models\Builders\FeatureBuilder;

class Feature extends Model
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

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            FeatureGroup::class,
            'feature_feature_group',
        );
    }

    public function inGroup(string $groupName): bool
    {
        return $this->groups->contains('name', $groupName);
    }

    public function newEloquentBuilder($query): FeatureBuilder
    {
        return new FeatureBuilder($query);
    }
}
