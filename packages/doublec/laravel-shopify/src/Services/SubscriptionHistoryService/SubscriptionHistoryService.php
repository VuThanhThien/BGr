<?php


namespace DoubleC\LaravelShopify\Services\SubscriptionHistoryService;

use DoubleC\LaravelShopify\Models\Charge;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;
use DoubleC\LaravelShopify\Services\Base\BaseService;

interface SubscriptionHistoryService extends BaseService
{
    /**
     * @param Shop $shop
     * @param string $name
     * @return SubscriptionHistory|null
     */
    public function getLatestSubscription(Shop $shop, string $name = 'main'): SubscriptionHistory|null;

    /**
     * @param Shop $shop
     * @param Plan $plan
     * @param array $data
     * @return SubscriptionHistory|bool
     */
    public function subscribe(Shop $shop, Plan $plan, array $data = []): SubscriptionHistory|bool;

    /**
     * @param Shop $shop
     * @return SubscriptionHistory|bool
     */
    public function unsubscribe(Shop $shop): SubscriptionHistory|bool;
}
