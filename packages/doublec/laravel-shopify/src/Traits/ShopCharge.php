<?php

namespace DoubleC\LaravelShopify\Traits;

use DoubleC\LaravelShopify\Models\Coupon;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Billing\RecurringApplicationCharge;
use Illuminate\Http\RedirectResponse;
use JetBrains\PhpStorm\ArrayShape;

trait ShopCharge
{
    use InstalledShop;

    /**
     * @param Shop $shop
     * @param Plan $plan
     * @return RedirectResponse
     */
    public function charge(Shop $shop, Plan $plan): RedirectResponse
    {
        $recurringApplicationCharge = new RecurringApplicationCharge(generateClientApi($shop));
        $shopifyCharge = $recurringApplicationCharge->create(
            [
                "recurring_application_charge" => $this->generateCharge($shop, $plan)
            ]
        );
        session(['plan' => $plan]);
        session(['shop' => $shop]);
        return redirect()->to($shopifyCharge['recurring_application_charge']['confirmation_url']);

    }

    /**
     * @return bool
     */
    public function isTest(): bool
    {
        return true;
    }

    /**
     * @param Shop $shop
     * @param Plan $plan
     * @return array
     */
    #[ArrayShape(["name" => "mixed", "trial_days" => "int", "price" => "float", "return_url" => "string"])]
    public function generateCharge(Shop $shop, Plan $plan): array
    {
        $returnUrl = config('shopify.app_url') . "/active_charge?plan_id=$plan->id&shop=$shop->shop";
        $discount = $this->discountRepository->getDiscountByShopAndPlan($shop, $plan);
        if ($discount) {
            /** @var Coupon $latestCoupon */
            $latestCoupon = $discount->coupons()->latest()->first();
            $couponCode = $latestCoupon->code;
            $returnUrl .= "&coupon=$couponCode";
            session(['coupon' => $couponCode]);
        }
        return [
            "name" => $plan->name,
            "trial_days" => $this->chargeService->countTrialDaysLeft($plan, $shop),
            "price" => $this->chargeService->caculatePrice($shop, $plan),
            "return_url" => $returnUrl,
            "test" => $this->isTest(),
        ];
    }

    /**
     * Billing when app installed
     * @return RedirectResponse
     */
    public function billing()
    {
        if (config('shopify.app_type') === 1) {
            return redirect()->route('dc.plans');
        }
        return $this->shopSubscribe(config('shopify.default_plan_id'));
    }
}
