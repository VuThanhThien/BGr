<?php


namespace DoubleC\LaravelShopify\Services\SubscriptionHistoryService;


use Carbon\Carbon;
use DoubleC\LaravelShopify\Models\Charge;
use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;
use DoubleC\LaravelShopify\Repositories\BaseRepository;
use DoubleC\LaravelShopify\Repositories\SubscriptionHistoryRepository\SubscriptionHistoryRepository;
use DoubleC\LaravelShopify\Services\Base\BaseService;
use DoubleC\LaravelShopify\Services\Base\BaseServiceImp;
use DoubleC\LaravelShopify\Services\ChargeService\ChargeService;
use Illuminate\Support\Arr;

class SubscriptionHistoryImp extends BaseServiceImp implements SubscriptionHistoryService
{

    public function __construct(private ChargeService $chargeService, SubscriptionHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getLatestSubscription(Shop $shop, string $name = 'main'): SubscriptionHistory|null
    {
        return $shop->subscriptions()->where('name', $name)->latest()->first();
    }

    public function subscribe(Shop $shop, Plan $plan, array $data = []): SubscriptionHistory|bool
    {
        $now = Carbon::now();

        $preparedData = [
            'shop_id' => $shop->id,
            'plan_id' => $plan->id,
            'trial_ends_at' => $now,
            'starts_at' => now(),
            'name' => 'main'
        ];

        if (!$plan->isFree()) {


            $trialDaysLeft = $this->chargeService->countTrialDaysLeft($plan, $shop);

            $preparedData['trial_ends_at'] = $now->addDays($trialDaysLeft);

            if (Arr::has($data, 'coupon')) {
                $preparedData['coupon_code'] = $data['coupon'];
            }

            if (Arr::has($data, 'charge')) {
                /** @var Charge $charge */
                $charge = $this->chargeService->createCharge($shop, $data['charge']);
                $preparedData['charge_id'] = $charge->id;
            } else {
                return false;
            }
        }

        //unsubscribe current subscription
        $this->unsubscribe($shop);

        return $this->repository->create($preparedData);
    }

    public function unsubscribe(Shop $shop): SubscriptionHistory|bool
    {
        $now = Carbon::now();

        /** @var SubscriptionHistory $currentSubscription */
        $currentSubscription = $this->getLatestSubscription($shop);

        if ($currentSubscription) {
            $preparedData = [
                'ends_at' => $now,
                'cancels_at' => now()
            ];

            /** @var Plan $currentPlan */
            $currentPlan = $currentSubscription->plan()->first();

            if ($currentPlan && !$currentPlan->isFree()) {

                /** @var Charge $currentCharge */
                $currentCharge = $currentSubscription->charge()->first();

                if ($currentCharge) {
                    //cancel charge
                    $this->chargeService->cancelCharge($currentCharge);

                    //add day charge left
                    $preparedData['ends_at'] = $now->addDays($this->chargeService->countChargeDayLeft($currentCharge));
                }

            }

            return $this->repository->update($currentSubscription->id, $preparedData);
        }

        return false;

    }
}
