<?php

return [
    'users' => [
        'super' => [
            'email' => env('SEED_SUPER_ADMIN_EMAIL', 'super@laravel-inertia-starter-kit.test'),
            'password' => env('SEED_SUPER_ADMIN_PASSWORD', '12345'),
        ],

        'admin' => [
            'email' => env('SEED_ADMIN_EMAIL', 'admin@laravel-inertia-starter-kit.test'),
            'password' => env('SEED_ADMIN_PASSWORD', '12345'),
        ],

        'user' => [
            'email' => env('SEED_USER_EMAIL', 'user@laravel-inertia-starter-kit.test'),
            'password' => env('SEED_USER_PASSWORD', '12345'),
        ],
    ],
];
