<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models;

use Illuminate\Database\Eloquent\Model;
use JustSteveKing\Laravel\FeatureFlags\Models\Builders\FeatureGroupBuilder;

class FeatureGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function newEloquentBuilder($query): FeatureGroupBuilder
    {
        return new FeatureGroupBuilder($query);
    }
}
