<?php


namespace DoubleC\LaravelShopify\Services\ShopService;

use Carbon\Carbon;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\ShopInfo;
use DoubleC\LaravelShopify\Repositories\ShopInfoRepository\ShopInfoRepository;
use DoubleC\LaravelShopify\Services\ChargeService\ChargeService;
use DoubleC\LaravelShopify\Services\SubscriptionHistoryService\SubscriptionHistoryService;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\StoreProperties\Shop as ShopResource;
use Exception;

class ShopServiceImp implements ShopService
{
    public function __construct(
        # Repositories
        private ShopInfoRepository $shopInfoRepository,
        # Services
        private SubscriptionHistoryService $subscriptionHistoryService,
        private ChargeService $chargeService
    )
    {
    }

    public function isInstalled(?Shop $shop): bool
    {
        if (!$shop || $this->isUninstalled($shop)) return false;
        return $this->isActivatedAccessToken($shop);
    }

    public function isUninstalled(?Shop $shop): bool
    {
        if (!$shop) return false;
        if ($shop->access_token === null) return true;
        return !$this->isActivatedAccessToken($shop);
    }

    public function isActivatedAccessToken(Shop $shop): bool
    {
        $client = generateClientApi($shop);
        $shopResource = new ShopResource($client);
        try {
            $response = $shopResource->all();
            return !isset($response['errors']);
        } catch (Exception) {
            return false;
        }
    }

    public function unInstallApp(Shop $shop): Shop
    {
        $shop->access_token = null;
        $shop->uninstalled_at = now();
        $shop->save();
        $this->deactivateShop($shop);
        return $shop;
    }

    public function deactivateShop(Shop $shop): Shop
    {
        $shop->activated_at = null;
        $latestSubscription = $this->subscriptionHistoryService->getLatestSubscription($shop);
        if ($latestSubscription && !$latestSubscription->isCanceled()) {
            # Cancel subscription
            $latestSubscription->cancels_at = now();
            # Cancel charge
            $charge = $latestSubscription->charge();
            if ($charge && $latestSubscription->isCharged() && !$charge->isCanceled()) {
                $this->chargeService->cancelCharge($charge);
            }
            $shop->used_days = now()->diffInDays(Carbon::instance($latestSubscription->starts_at));
        }
        $shop->save();
        return $shop;
    }

    public function pullShopInfo(Shop $shop): ShopInfo|null
    {
        $client = generateClientApi($shop);
        $shopResource = new ShopResource($client);
        try {
            $shopInfo = $shopResource->all();
            $info = isset($shopInfo['errors']) ? [] : $shopInfo['shop'];
        } catch (Exception) {
            return null;
        }
        return $this->shopInfoRepository->updateOrCreateShopInfo($shop, $info);
    }

    public function installApp(Shop $shop, string $access_token): Shop
    {
        $shop->access_token = $access_token;
        $shop->installed_at = now();
        $shop->save();
        return $shop;
    }
}
