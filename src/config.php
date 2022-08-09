<?php

return [
    'client_id' => env('AZURE_CLIENT_ID', ''),
    'secret' => env('AZURE_SECRET', ''),
    'tenant' => env('AZURE_TENANT', 'common'),

    /*
    |--------------------------------------------------------------------------
    | Identification
    |--------------------------------------------------------------------------
    |
    | How to identify the user.
    | `locale` column in users table with `remote property` property from Azure API.
    | Remote can be extended with regex to match a sub pattern.
    |
    */

    'id' => [
        'local' => 'email',
        'remote' => [
            'property' => 'email',
            'regex' => null,
            'index' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    |
    | Where to redirect after succesfully login.
    |
    */

    'redirect' => '/',

    'routes' => [
        'prefix' => 'auth',
    ],
];