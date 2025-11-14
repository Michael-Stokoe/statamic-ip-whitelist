<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Storage Driver
    |--------------------------------------------------------------------------
    |
    | Choose how IP addresses should be stored: 'file' or 'database'
    |
    */
    'storage' => env('IP_WHITELIST_STORAGE', 'file'),

    /*
    |--------------------------------------------------------------------------
    | File Storage Path
    |--------------------------------------------------------------------------
    |
    | Path where IP whitelist file will be stored (when using file storage)
    |
    */
    'file_path' => storage_path('app/ip-whitelist.json'),

    /*
    |--------------------------------------------------------------------------
    | Protected Routes
    |--------------------------------------------------------------------------
    |
    | Additional routes that should be protected by IP whitelist
    |
    */
    'protected_routes' => [
        // Add additional route patterns here
        // 'admin/*',
        // 'api/admin/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Bypass for Local Development
    |--------------------------------------------------------------------------
    |
    | Allow bypassing IP whitelist when APP_ENV is 'local'
    |
    */
    'bypass_local' => env('IP_WHITELIST_BYPASS_LOCAL', true),

    /*
    |--------------------------------------------------------------------------
    | Default Allowed IPs
    |--------------------------------------------------------------------------
    |
    | IPs that are always allowed (useful for initial setup)
    |
    */
    'default_allowed_ips' => [
        '127.0.0.1',
        '::1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission
    |--------------------------------------------------------------------------
    |
    | Permission required to manage IP whitelist
    |
    */
    'permission' => 'manage ip whitelist',
];
