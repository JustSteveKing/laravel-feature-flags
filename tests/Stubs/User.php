<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests\Stubs;

use Illuminate\Foundation\Auth\User as AuthUser;
use JustSteveKing\Laravel\FeatureFlags\Concerns\HasFeatures;

class User extends AuthUser
{
    use HasFeatures;

    public $guarded = [];

    public $table = 'users';
}
