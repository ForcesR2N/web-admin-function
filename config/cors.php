<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:8001',     // Mobile app local development
        'http://10.0.2.2:8001',     // Android emulator
        'http://127.0.0.1:8001',    // Local development alternative
        'capacitor://localhost',     // Capacitor mobile app
        'http://localhost:*',        // All localhost ports
        '*'// Add your production mobile app domains here
    ],

    'allowed_origins_patterns' => [
        '/^http:\/\/192\.168\.\d+\.\d+:8001$/',  // Local network IPs
        '/^http:\/\/10\.\d+\.\d+\.\d+:8001$/',   // Private network IPs
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
