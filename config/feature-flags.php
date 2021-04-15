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
];
