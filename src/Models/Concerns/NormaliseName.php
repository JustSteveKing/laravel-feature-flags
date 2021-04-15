<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait NormaliseName
{
    public static function bootNormaliseName()
    {
        static::creating(function(Model $model) {
            $model->name = strtolower($model->name);
        });
    }
}
