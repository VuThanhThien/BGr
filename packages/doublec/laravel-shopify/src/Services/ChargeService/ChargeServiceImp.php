<?php


namespace DoubleC\LaravelShopify\Services\ChargeService;


use Carbon\Carbon;
use DoubleC\LaravelShopify\Models\Charge;
use DoubleC\LaravelShopify\Models\Discount;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;
use DoubleC\LaravelShopify\Repositories\ChargeRepository\ChargeRepository;
use DoubleC\LaravelShopify\Repositories\CouponRepository\CouponRepository;
use DoubleC\LaravelShopify\Repositories\DiscountRepository\DiscountRepository;
use DoubleC\LaravelShopify\Repositories\PlanRepository\PlanRepository;
use DoubleC\LaravelShopify\Repositories\SubscriptionHistoryRepository\SubscriptionHistoryRepository;
use DoubleC\LaravelShopify\Services\Base\BaseServiceImp;

class ChargeServiceImp extends BaseServiceImp implements ChargeService
{

    public function __construct
    (
        private PlanRepository $planRepository,
        private SubscriptionHistoryRepository $subscriptionHistoryRepository,
        private CouponRepository $couponRepository,
        private DiscountRepository $discountRepository,
        ChargeRepository $repository
    )
    {
        $this->repository = $repository;
    }

    public function cancelCharge(Charge $charge): Charge
    {
        $charge->cancelled_on = now();
        $charge->save();
        return $charge;
    }

    public function countTrialDaysLeft(Plan $plan, Shop $shop): int
    {
        $firstCharge = $this->firstTimeCharge($shop);

        if (!$firstCharge) {
            return $plan->trial_days;
        }

        $trialDaysLeft = $plan->trial_days - Carbon::parse($firstCharge->starts_at)->diffInDays(now());

        if ($trialDaysLeft <= 0) {

            $firstTimeUsePlan = $this->subscriptionHistoryRepository->findByCondition(['plan_id' => $plan->id]);

            if (!$firstTimeUsePlan) {
                return 1;
            }

        }

        return $trialDaysLeft < 0 ? 0 : $trialDaysLeft;
    }

    public function firstTimeCharge(Shop $shop): SubscriptionHistory|null
    {
        return $shop->subscriptions()->whereHas('charge', function ($query) {
            $query->where('status', 'active');
        })->orderBy('starts_at')->first();
    }

    public function isFirstTimeCharge(Shop $shop): bool
    {

        return $this->firstTimeCharge($shop) ? true : false;

    }

    public function hasUsePlan(Shop $shop, int $planId): bool
    {
        // TODO: Implement hasUsePlan() method.
    }

    public function caculatePrice(Shop $shop, Plan $plan): float|int
    {
        /** @var Discount $discount */
        $discount = $this->discountRepository->getDiscountByShopAndPlan($shop, $plan);

        $price = $plan->price;

        if ($discount) {

            $price = $discount->type == 'percentage' ? $price - ($price * $discount->value) / 100 : $price - $discount->value;

        }

        return $price;

    }

    public function countChargeDayLeft(Charge $charge): int
    {
        if (!$charge->billing_on) {
            return 0;
        }

        $dateDiffFromBillingDay = Carbon::parse($charge->billing_on)->diffInDays(now()) % 30;

        $days = $dateDiffFromBillingDay < 0 ? 0 : $dateDiffFromBillingDay;

        return 30 - $days;
    }

    public function createCharge(Shop $shop, array $chargeInfo): Charge|false|null
    {
        return $this->repository->create([
            'shop_id' => $shop->id,
            'charge_id' => $chargeInfo['id'],
            'price' => $chargeInfo['price'],
            'name' => $chargeInfo['name'],
            'trial_days' => $chargeInfo['trial_days'],
            'type' => 'recurring',
            'billing_on' => isset($chargeInfo['billing_on']) ? $chargeInfo['billing_on'] : null,
            'trial_ends_on' => isset($chargeInfo['trial_ends_on']) ? $chargeInfo['trial_ends_on'] : null,
            'cancelled_on' => isset($chargeInfo['cancelled_on']) ? $chargeInfo['cancelled_on'] : null,
            'test' => $chargeInfo['test'],
            'status' => $chargeInfo['status'],
            'description' => $chargeInfo
        ]);
    }
}
