<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Models\Builders\Concerns;

trait QueryByName
{
    public function name(string $name): self
    {
        $this->where('name', $name);

        return $this;
    }
}
