<?php


namespace DoubleC\LaravelShopify\Services\ShopService;

use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\ShopInfo;
use DoubleC\LaravelShopify\Repositories\ShopInfoRepository\ShopInfoRepository;
use DoubleC\LaravelShopify\Services\ChargeService\ChargeService;
use DoubleC\LaravelShopify\Services\SubscriptionHistoryService\SubscriptionHistoryService;

interface ShopService
{
    public function __construct(
        # Repositories
        ShopInfoRepository $shopInfoRepository,
        # Services
        SubscriptionHistoryService $subscriptionHistoryService,
        ChargeService $chargeService
    );

    /**
     * Check is installed app for shop name
     * @param Shop|null $shop
     * @return bool
     */
    public function isInstalled(?Shop $shop): bool;

    /**
     * Check is uninstalled app
     * @param Shop|null $shop
     * @return bool
     */
    public function isUninstalled(?Shop $shop): bool;

    /**
     * Check is active accessToken
     * @param Shop $shop
     * @return bool
     */
    public function isActivatedAccessToken(Shop $shop): bool;

    /**
     * Uninstall app and forget session
     * @param Shop $shop
     * @return Shop
     */
    public function unInstallApp(Shop $shop): Shop;

    /**
     * Deactivate shop, remove plan subscription
     * @param Shop $shop
     * @return Shop
     */
    public function deactivateShop(Shop $shop): Shop;

    /**
     * Pull shop info from shopify | Save shop infos
     * @param Shop $shop
     * @return ShopInfo|null
     */
    public function pullShopInfo(Shop $shop): ShopInfo|null;

    /**
     * Install app for shop
     * @param Shop $shop
     * @param string $access_token
     * @return Shop
     */
    public function installApp(Shop $shop, string $access_token): Shop;
}
