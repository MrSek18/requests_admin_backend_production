<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'supports_credentials' => true,

    'allowed_origins' => [
        'http://localhost:3000',
        'https://mi-frontend.loca.lt',
        'https://red-donkey-35.loca.lt',
        'https://requests-admin-frontend-production.vercel.app',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,
];
