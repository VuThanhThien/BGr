<?php

use DoubleC\LaravelShopify\Facades\ShopSetting;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

if (!function_exists('isValidDomain')) {
    /**
     * Check is valid shopify domain
     * @param string $domain
     * @return bool
     */
    function isValidDomain(string $domain): bool
    {
        return str_ends_with($domain, '.myshopify.com') && strlen($domain) > 14;
    }
}

if (!function_exists('shopNameFromDomain')) {
    /**
     * Get shop name only from shopify domain (Remove .myshopify.com)
     * @param string $domain shop domain
     * @return string
     */
    #[Pure]
    function shopNameFromDomain(string $domain): string
    {
        return substr($domain, 0, -14);
    }
}
if (!function_exists('domainFromShopName')) {
    /**
     * Get shopify domain from shop name
     * @param string $shop_name
     * @return string
     */
    #[Pure]
    function domainFromShopName(string $shop_name): string
    {
        return "{$shop_name}.myshopify.com";
    }
}
if (!function_exists('isValidWebhookRequest')) {
    /**
     * @param Request $request
     * @return bool
     */
    function isValidWebhookRequest(Request $request): bool
    {
        $hmacHeader = $request->header('HTTP_X_SHOPIFY_HMAC_SHA256');
        if (!$hmacHeader || empty ($hmacHeader)) {
            $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');
        }
        $calculatedHmac = base64_encode(hash_hmac('sha256', $request->getContent(), config('shopify.shared_secret'), true));
        return $hmacHeader == $calculatedHmac;
    }
}

if (!function_exists('generateClientApi')) {
    /**
     * @param Shop $shop
     * @return ClientApi|null
     */
    function generateClientApi(Shop $shop): ?ClientApi
    {
        try {
            $apiVersion = config('shopify.api_version');
            $shopName = shopNameFromDomain($shop->shop);
            $accessToken = $shop->access_token;
            return new ClientApi($shopName, $apiVersion, $accessToken);
        } catch (Exception) {
            return null;
        }
    }
}

if (!function_exists('setting')) {
    /**
     * @param array|string $keys
     * @param mixed $default
     * @return mixed
     */
    function setting(array|string $keys, mixed $default = null): mixed
    {
        if (is_array($keys)) {
            $effected = 0;
            foreach ($keys as $key => $value) {
                $effected += ShopSetting::set(key: $key, value: $value);
            }
            return $effected;
        }
        return ShopSetting::get(key: $keys, default: $default);
    }
}

if (!function_exists('shopSetting')) {
    /**
     * @param int $shop_id
     * @param array|string $keys
     * @param mixed|null $default
     * @return mixed
     */
    function shopSetting(int $shop_id, array|string $keys, mixed $default = null): mixed
    {
        if (is_array($keys)) {
            $effected = 0;
            foreach ($keys as $key => $value) {
                $effected += ShopSetting::set(key: $key, value: $value, shop_id: $shop_id);
            }
            return $effected;
        }
        return ShopSetting::get(key: $keys, default: $default, shop_id: $shop_id);
    }
}
