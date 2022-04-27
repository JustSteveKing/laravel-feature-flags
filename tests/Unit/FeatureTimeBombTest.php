<?php

declare(strict_types=1);

use Carbon\Carbon;
use JustSteveKing\Laravel\FeatureFlags\Models\Feature;
use Illuminate\Support\Facades\Config;

beforeEach(function(): void {
    Config::set('feature-flags.enable_time_bombs', true);
});

afterAll(function(): void {
    Config::set('feature-flags.enable_time_bombs', false);
});

it('can create a feature with an expiry date', function(): void {
   Feature::create([
       'name' => 'Expiring Feature',
       'expires_at' => Carbon::now()->addDay()
   ]);

   expect(Feature::all())
       ->toHaveCount(1);
});

it('Does not throw an Exception when time bombs are disabled', function(): void {
    Config::set('feature-flags.enable_time_bombs', false);

    Feature::create([
        'name' => 'Expired Feature',
        'expires_at' => Carbon::now()->subDay()
    ]);

    expect(Feature::all())->toHaveCount(1);
    // Assert No Exception is thrown
    $this->assertTrue(true);
});

it('throws an Exception when time bombs are enabled', function(): void {
    Feature::create([
        'name' => 'Expired Feature',
        'expires_at' => Carbon::now()->subDay()
    ]);

    Feature::all();
})->throws(Exception::class, 'The Feature has expired - expired feature');

it('casts the Expiry date to Carbon', function(): void {
   Feature::create([
       'name' => 'Expired Feature',
       'expires_at' => Carbon::now()->addDay()
   ]);

   expect(Feature::first()->expires_at)->toBeInstanceOf(Carbon::class);
});

it('throws an Exception when 1 second past an expiry date', function(): void {
    Feature::create([
        'name' => 'Expired Feature',
        'expires_at' => Carbon::now()->subSecond()
    ]);

    Feature::all();
})->throws(Exception::class, 'The Feature has expired - expired feature');

it('Does not throw an Exception when an expiry date is 1 second in the future', function(): void {
    Feature::create([
        'name' => 'Expired Feature',
        'expires_at' => Carbon::now()->addSecond()
    ]);

    Feature::all();
    // Assert No Exception is thrown
    $this->assertTrue(true);
});

it('Does not throw an Exception when Expiry date is Null', function(): void {
   Feature::create([
       'name' => 'Null Expiry Feature',
       'expires_at' => null
   ]);

    Feature::all();
    // Assert No Exception is thrown
    $this->assertTrue(true);
});
