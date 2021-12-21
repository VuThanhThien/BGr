<?php


namespace DoubleC\LaravelShopify\Repositories\ShopInfoRepository;


use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\ShopInfo;
use DoubleC\LaravelShopify\Repositories\Repository;

interface ShopInfoRepository extends Repository
{
    /**
     * Update or create shop info
     * @param Shop $shop
     * @param array $info
     * @return ShopInfo
     */
    public function updateOrCreateShopInfo(Shop $shop, array $info): ShopInfo;
}
