<?php

namespace DoubleC\LaravelShopify\Traits;


use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Repositories\ShopRepository\ShopRepository;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Billing\RecurringApplicationCharge;
use Illuminate\Http\RedirectResponse;
use DoubleC\LaravelShopify\Models\User;

trait ShopSubscription
{

    use InstalledShop;
    use ShopCharge;
    use Redirect;

    public function shopSubscribe(int $planId)
    {
        /** @var Plan $plan */
        $plan = $this->planRepository->find($planId);
        $shop = $this->shop();
        if ($plan && $shop) {
            if (!$plan->isFree()) {
                return $this->charge($shop, $plan);
            }
            $this->subscriptionHistoryService->subscribe($shop, $plan);
            $user = User::where('shop_id', $shop->id)->first();
            $setting = $user->setting()->select('id', 'shop_id', 'is_skip_started')->first();
            return view('app', ['token'=>$this->jwtToken, 'isSkipStarted'=>$setting->is_skip_started]);
        }
        return redirect()->back()->with('message', 'Chosen plan is not available');
    }


}
