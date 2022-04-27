<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models;

use Carbon\Carbon;
use Exception;
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
        'expires_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'expires_at' => 'datetime'
    ];

    public static function booted()
    {
        static::retrieved(function(Feature $feature) {
            if(config('feature-flags.enable_time_bombs')) {
                return (! is_null($feature->expires_at) && Carbon::now() >= $feature->expires_at) ? throw new Exception(sprintf('The Feature has expired - %s', $feature->name)) : true;
            }
            return true;
        });
    }

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
