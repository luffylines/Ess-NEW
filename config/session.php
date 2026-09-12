<?php

use Illuminate\Support\Str;

return [

    // Render needs persistent database-backed sessions. Keep this independent of
    // any stale/overriding SESSION_DRIVER environment value so CSRF sessions
    // survive between requests and across container instances.
    'driver' => 'database',

    'lifetime' => (int) env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    'encrypt' => env('SESSION_ENCRYPT', false),

    'files' => storage_path('framework/sessions'),

    'connection' => env('SESSION_CONNECTION'),

    'table' => env('SESSION_TABLE', 'sessions'),

    'store' => env('SESSION_STORE'),

    'lottery' => [2, 100],

    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel')).'-session'
    ),

    'path' => env('SESSION_PATH', '/'),

    // Treat an empty value or the literal string "null" as no cookie domain.
    // This is important on Render, where the app is served from its own HTTPS host.
    'domain' => (function () {
        $domain = env('SESSION_DOMAIN');

        return ($domain === null || strtolower(trim((string) $domain)) === 'null' || trim((string) $domain) === '')
            ? null
            : $domain;
    })(),

    // Render serves the application over HTTPS, so secure cookies are enabled by default.
    'secure' => env('SESSION_SECURE_COOKIE', true),

    'http_only' => env('SESSION_HTTP_ONLY', true),

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),

];
