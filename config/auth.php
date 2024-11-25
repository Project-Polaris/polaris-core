<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'api'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',

            /*
            |--------------------------------------------------------------------------
            | Algorithm
            |--------------------------------------------------------------------------
            |
            | Algorithm used to sign new JWTs. 
            |
            | Supported: HS256, HS384, HS512, RS256, RS384, RS512, ES256, ES256K,
            |            ES384, EdDSA
            | See also: https://github.com/firebase/php-jwt/blob/main/src/JWT.php#L55
            |
            */

            'alg' => env("AUTH_API_JWT_ALGORITHM", "EdDSA"),

            /*
            |--------------------------------------------------------------------------
            | Keys
            |--------------------------------------------------------------------------
            |
            | A list of all keys used to sign the JWT. 
            | All previous used keys are needed to listed here, to preserve backward
            | compatibility.
            |
            | By default, keys are indexed by the xxh64 hash of the public key.
            |
            | TODO: Theoretically it is possible to accept JWK here. 
            | TODO: Only the public portion of previous keys need to be listed here.
            |       Find a way to trim down the processing phase.
            |
            */

            'keys' => [
                ...collect(explode(',', env('AUTH_API_JWT_KEYS_ED25519', '')))
                ->filter()
                ->mapWithKeys(function ($item) {
                    $private_key = base64_decode($item);
                    $public_key = sodium_crypto_sign_publickey_from_secretkey($private_key);
                    $kid = rtrim(strtr(base64_encode(hash('xxh64', $public_key, true)), '+/', '-_'), '=');

                    return [
                        $kid => [
                            'type' => 'EdDSA',
                            'public' => base64_encode($public_key),
                            'private' => base64_encode($private_key),
                        ],
                    ];
                })
                ->toArray(),
            ],

            'current_key' => env('AUTH_API_JWT_CURRENT_KEY', 'default'),

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
