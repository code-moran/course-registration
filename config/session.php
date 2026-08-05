<?php

$rawDomain = env('SESSION_DOMAIN');
$sessionDomain = blank($rawDomain) || $rawDomain === 'null' ? null : $rawDomain;

$secureCookie = env('SESSION_SECURE_COOKIE');
if ($secureCookie === null) {
    // Auto-enable Secure cookies when the app is HTTPS-only (Railway / production).
    $secureCookie = filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOLEAN)
        || str_starts_with((string) env('APP_URL', ''), 'https://');
} else {
    $secureCookie = filter_var($secureCookie, FILTER_VALIDATE_BOOLEAN);
}

return [
    /*
    | On Railway (and any multi-replica host), use database/redis — file sessions
    | cause intermittent 419 CSRF mismatches when requests hit different containers.
    */
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
    'encrypt' => env('SESSION_ENCRYPT', false),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE', 'sessions'),
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env(
        'SESSION_COOKIE',
        \Illuminate\Support\Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
    'path' => env('SESSION_PATH', '/'),
    'domain' => $sessionDomain,
    'secure' => $secureCookie,
    'http_only' => env('SESSION_HTTP_ONLY', true),
    'same_site' => env('SESSION_SAME_SITE', 'lax'),
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),
];
