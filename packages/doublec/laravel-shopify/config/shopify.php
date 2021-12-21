<?php
/**
 * [app_type] Free available => 0 | Paid app: 1
 * [default_plan_id] Auto apply plan if app has free plan
 */
return [
    'app_name'                  => env('APP_NAME'),
    'api_key'                   => env('SHOPIFY_API_KEY'),
    'shared_secret'             => env('SHOPIFY_SHARED_SECRET'),
    'redirect_url'              => env('SHOPIFY_REDIRECT_URL'),
    'permissions'               => [env('SHOPIFY_PERMISSIONS')],
    'api_version'               => '2021-07',
    'app_url'                   => env('APP_URL'),
    'shopify_sudo_store'        => array_map(fn($store) => trim($store), explode(',', env('SHOPIFY_SUDO_STORE'))),
    'activated_redirect_link'   => '/',
    'app_type'                  => 1,
    'default_plan_id'           => 1
];
