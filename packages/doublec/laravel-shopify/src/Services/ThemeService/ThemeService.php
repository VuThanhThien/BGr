<?php


namespace DoubleC\LaravelShopify\Services\ThemeService;


use DoubleC\LaravelShopify\Models\Shop;

interface ThemeService
{
    /**
     * Pull Theme use info
     * @param Shop $shop
     * @return array|null
     */
    public function pullThemeInfo(Shop $shop): ?array;
}
