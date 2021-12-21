<?php

namespace DoubleC\LaravelShopify\Repositories\DiscountRepository;

use DoubleC\LaravelShopify\Models\Discount;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Repositories\BaseRepository;
use DoubleC\LaravelShopify\Repositories\CouponRepository\CouponRepository;

class DiscountRepositoryEloquent extends BaseRepository implements DiscountRepository {

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Discount::class;
    }

    /**
     * @param Shop $shop
     * @param Plan|null $plan
     * @return Discount|null
     */
    public function getDiscountByShopAndPlan(Shop $shop, Plan $plan = null): Discount|null
    {
        /** @var Discount $discount */
        $discount =  $this->select()
            ->when($plan, function ($query) use ($plan) {
                $query->where('plan_id', $plan->id);
            })
            ->where('started_at', '<=', now())
            ->where('expired_at', '>', now())
            ->with('coupons', function ($query) use ($shop) {
                $query->where('shop', $shop->shop);
                $query->where('status', 1);
            })->orderByDesc('created_at')->first();

        if (!$discount) {
            return null;
        }

        return $this->discountUsageTimes($discount) ? $discount : null;

    }

    public function discountUsageTimes(Discount $discount): bool {

        $couponRepository = app(CouponRepository::class);

        $usage = $couponRepository->select()->where('discount_id', $discount->id)->sum('times_used');

        return $usage < $discount->usage_limit;

    }

}
