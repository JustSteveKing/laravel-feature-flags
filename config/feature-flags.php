<?php

return [
    'middleware' => [
        /*
        |--------------------------------------------------------------------------
        | Default Return Mode
        |--------------------------------------------------------------------------
        |
        | This option controls the default return mode from the middleware.
        |
        | Supported: "abort", "redirect"
        |
        */
        'mode' => 'abort',

        /*
        |--------------------------------------------------------------------------
        | Default Redirect Route
        |--------------------------------------------------------------------------
        |
        | This option controls the default redirect route from the middleware
        | when using the "redirect" mode.
        |
        */
        'redirect_route' => '/',

        /*
        |--------------------------------------------------------------------------
        | Default Status Code
        |--------------------------------------------------------------------------
        |
        | This option controls the default status code from the middleware
        | when using the "abort" mode.
        |
        */
        'status_code' => 404,
    ],

    /*
    |--------------------------------------------------------------------------
    | Enabling Time bombs for Features
    |--------------------------------------------------------------------------
    |
    | This option controls whether an exception will be thrown if a feature
    | has expired. See Martin Fowler's blog post on this:
    | https://martinfowler.com/articles/feature-toggles.html#WorkingWithFeature-flaggedSystems
    |
    */
    'enable_time_bombs' => false,

    /*
     |--------------------------------------------------------------------------
     | Environments that will NOT trigger Time Bombs
     |--------------------------------------------------------------------------
     |
     | This option controls which environment settings will prevent time bomb
     | exceptions from being thrown. To trigger in all environments, leave
     | the array as empty.
     |
     */
     'time_bomb_environments' => ['production']
];
