<?php

namespace JustSteveKing\Laravel\FeatureFlags\Exceptions;

use Exception;

class ExpiredFeatureException extends Exception
{
    public static function create(string $feature)
    {
        return new static("The Feature {$feature} has expired.");
    }
}
