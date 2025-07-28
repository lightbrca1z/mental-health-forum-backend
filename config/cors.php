<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:3001',
        'https://mental-health-forum-frontend.vercel.app',
        'https://mental-health-forum-frontend-production.up.railway.app',
        'https://mental-health-forum-frontend.railway.app'
    ],
    'allowed_origins_patterns' => [
        'https://*.railway.app',
        'https://*.vercel.app'
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
]; 