<?php

namespace DoubleC\LaravelShopify\Repositories\DiscountRepository;

use DoubleC\LaravelShopify\Models\Discount;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Repositories\Repository;

interface DiscountRepository extends Repository
{
    /**
     * @param Shop $shop
     * @param Plan|null $plan
     * @return Discount|null
     */
    public function getDiscountByShopAndPlan(Shop $shop, Plan $plan = null): Discount|null;

    /**
     * @param Discount $discount
     * @return bool
     */
    public function discountUsageTimes(Discount $discount): bool;
}
