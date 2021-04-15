<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
