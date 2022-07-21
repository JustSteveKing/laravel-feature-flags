<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;
use JustSteveKing\Laravel\FeatureFlags\Exceptions\ExpiredFeatureException;
use JustSteveKing\Laravel\FeatureFlags\Models\Builders\FeatureBuilder;
use JustSteveKing\Laravel\FeatureFlags\Models\Concerns\NormaliseName;

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

    public static function booted(): void
    {
        static::retrieved(function (Feature $feature) {
            $timeBombsAreEnabled = config('feature-flags.enable_time_bombs');
            $environmentAllowsTimeBombs = !App::environment(config('feature-flags.time_bomb_environments'));

            if ($timeBombsAreEnabled && $environmentAllowsTimeBombs) {
                $featureHasExpired = Carbon::now()->isAfter($feature->expires_at);

                if ($featureHasExpired) {
                    throw ExpiredFeatureException::create($feature->name);
                }
                return true;
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
