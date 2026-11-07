<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Account Limit
    |--------------------------------------------------------------------------
    |
    | The institute is operated by exactly two administrators. There is no
    | public registration route; this limit is also enforced at the model
    | layer so an accidental extra account cannot be created.
    |
    */

    'admin_limit' => (int) env('ADMIN_ACCOUNT_LIMIT', 2),

    /*
    |--------------------------------------------------------------------------
    | Public Page Cache
    |--------------------------------------------------------------------------
    |
    | Public HTML is cached to absorb traffic spikes. Change CACHE_STORE to
    | "redis" in production when running more than one application server.
    |
    */

    'cache_ttl' => (int) env('ARTICLE_CACHE_TTL', 600),

    /*
    |--------------------------------------------------------------------------
    | Image Optimisation
    |--------------------------------------------------------------------------
    |
    | Uploaded images are resized and encoded as WebP before they are stored
    | on the public disk. Lower quality means smaller files.
    |
    */

    'image_max_width' => (int) env('IMAGE_MAX_WIDTH', 1600),

    'image_quality' => (int) env('IMAGE_WEBP_QUALITY', 82),

    /*
    |--------------------------------------------------------------------------
    | Initial Administrators
    |--------------------------------------------------------------------------
    |
    | Read through the config repository (not env()) so the values keep
    | working after "php artisan config:cache" on the production server.
    | The seeder refuses to run without a password.
    |
    */

    'seed_admins' => [
        'password' => env('ADMIN_PASSWORD'),

        'accounts' => [
            [
                'name' => env('ADMIN_1_NAME', 'بەڕێوەبەری یەکەم'),
                'email' => env('ADMIN_1_EMAIL', 'admin1@lozan.com'),
            ],
            [
                'name' => env('ADMIN_2_NAME', 'بەڕێوەبەری دووەم'),
                'email' => env('ADMIN_2_EMAIL', 'admin2@lozan.com'),
            ],
        ],
    ],

];
