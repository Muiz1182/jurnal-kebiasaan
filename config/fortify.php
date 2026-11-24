<?php

use Laravel\Fortify\Features;

return [

    'guard' => 'web',

    'passwords' => 'users',

    /*
    |--------------------------------------------------------------------------
    | Username
    |--------------------------------------------------------------------------
    | Menggunakan 'username' sebagai kredensial utama untuk login.
    */
    'username' => 'username',

    /*
    |--------------------------------------------------------------------------
    | Email
    |--------------------------------------------------------------------------
    | Field email yang digunakan untuk pengiriman notifikasi/reset password.
    */
    'email' => 'email',

    'lowercase_usernames' => true,

    /*
    |--------------------------------------------------------------------------
    | Home Path
    |--------------------------------------------------------------------------
    | Redirect setelah user berhasil login.
    */
    'home' => '/dashboard',

    'prefix' => '',

    'domain' => null,

    'middleware' => ['web'],

    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
        'passkeys' => 'passkeys',
    ],

    /*
    |--------------------------------------------------------------------------
    | Views
    |--------------------------------------------------------------------------
    | Kita atur ke true agar Fortify tetap melacak route view, 
    | tetapi kita sudah meng-override-nya di FortifyServiceProvider.
    */
    'views' => true,

    'passkeys' => [
        'relying_party_id' => parse_url(config('app.url'), PHP_URL_HOST),
        'allowed_origins' => [config('app.url')],
        'timeout' => 60000,
    ],

    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        // Features::emailVerification(), // Opsional, aktifkan jika perlu verifikasi email
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]),
        Features::passkeys([
            'confirmPassword' => true,
        ]),
    ],
];