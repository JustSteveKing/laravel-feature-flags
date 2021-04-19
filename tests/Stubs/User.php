<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\FeatureFlags\Tests\Stubs;

use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
    public $guarded = [];

    public $table = 'users';
}
