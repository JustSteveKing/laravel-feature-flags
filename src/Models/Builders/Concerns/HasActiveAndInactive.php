<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models\Builders\Concerns;

trait HasActiveAndInactive
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
