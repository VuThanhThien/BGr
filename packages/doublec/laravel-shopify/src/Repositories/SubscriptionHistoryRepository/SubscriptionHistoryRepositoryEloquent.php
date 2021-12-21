<?php

namespace DoubleC\LaravelShopify\Repositories\SubscriptionHistoryRepository;

use DoubleC\LaravelShopify\Models\Charge;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;
use DoubleC\LaravelShopify\Repositories\BaseRepository;

class SubscriptionHistoryRepositoryEloquent extends BaseRepository implements SubscriptionHistoryRepository
{
    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return SubscriptionHistory::class;
    }

    public function getCurrentSubscription(Shop $shop): SubscriptionHistory|null
    {
        return $shop->subscriptions()->latest()->first();
    }
}
