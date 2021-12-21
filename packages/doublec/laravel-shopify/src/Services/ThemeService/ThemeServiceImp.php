<?php


namespace DoubleC\LaravelShopify\Services\ThemeService;


use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Theme;

class ThemeServiceImp implements ThemeService
{

    public function pullThemeInfo(Shop $shop): ?array
    {
        $client = generateClientApi($shop);
        $themeApi = new Theme($client);
        $response = $themeApi->all(['id', 'name', 'role']);
        if (isset($response['themes'])) {
            $themes = collect($response['themes']);
            return $themes->filter(function ($theme) {
                return $theme['role'] === 'main' || $theme['role'] === 'mobile';
            })->first();
        }
        return null;
    }
}
