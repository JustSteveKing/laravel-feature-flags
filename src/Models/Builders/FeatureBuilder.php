<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class FeatureBuilder extends Builder
{
    public function active(): self
    {
        $this->where('active', true);

        return $this;
    }

    public function inactive(): self
    {
        $this->where('active', false);

        return $this;
    }
}
