<?php

namespace DoubleC\LaravelShopify\Repositories\SubscriptionHistoryRepository;

use DoubleC\LaravelShopify\Models\Charge;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;
use DoubleC\LaravelShopify\Repositories\Repository;

interface SubscriptionHistoryRepository extends Repository
{

    /**
     * @param Shop $shop
     * @return SubscriptionHistory|null
     */
    public function getCurrentSubscription(Shop $shop): SubscriptionHistory|null;

}
