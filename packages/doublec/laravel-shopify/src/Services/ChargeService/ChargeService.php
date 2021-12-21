<?php


namespace DoubleC\LaravelShopify\Services\ChargeService;


use DoubleC\LaravelShopify\Models\Charge;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;

interface ChargeService
{
    /**
     * Cancel charge
     * @param Charge $charge
     * @return Charge
     */
    public function cancelCharge(Charge $charge): Charge;

    /**
     * @param Plan $plan
     * @param Shop $shop
     * @return int
     */
    public function countTrialDaysLeft(Plan $plan, Shop $shop): int;

    /**
     * @param Charge $charge
     * @return int
     */
    public function countChargeDayLeft(Charge $charge): int;

    /**
     * @param Shop $shop
     * @return Charge|null
     */
    public function firstTimeCharge(Shop $shop): SubscriptionHistory|null;

    /**
     * @param Shop $shop
     * @return bool
     */
    public function isFirstTimeCharge(Shop $shop): bool;

    /**
     * @param Shop $shop
     * @param int $planId
     * @return bool
     */
    public function hasUsePlan(Shop $shop, int $planId): bool;

    /**
     * @param Shop $shop
     * @param Plan $plan
     * @return float|int
     */
    public function caculatePrice(Shop $shop, Plan $plan): float|int;

    /**
     * @param Shop $shop
     * @param array $chargeInfo
     * @return Charge|false|null
     */
    public function createCharge(Shop $shop, array $chargeInfo): Charge|false|null;

}
