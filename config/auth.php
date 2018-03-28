<?php
/**
 * Created by PhpStorm.
 * User: jaime
 * Date: 14/02/18
 * Time: 15:52
 */
return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\User::class
        ],
        'customers' => [
            'driver' => 'eloquent',
            'model' => \App\Customer::class
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | User Claims
    |--------------------------------------------------------------------------
    |
    | User claims will be loaded from the properties of the auth providers model
    | specified in the auth config file.
    |
    */
    'user_claims' => [
        'name' => 'name',
        'email' => 'email',
        'role' => 'role'
    ],

    /*
    |--------------------------------------------------------------------------
    | App claims
    |--------------------------------------------------------------------------
    |
    | App claims are static and will be given the specified value across all
    | tokens issued by the app.
    |
    */
    'app_claims' => [
      //  'iss' => url('')
    ]

];