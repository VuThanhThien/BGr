<?php

namespace App\Listeners;

use App\Events\CheckAutoTierEvent;
use App\Models\AffiliateTier;
use App\Models\Conversion;
use App\Models\ShopSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon\Carbon;

class CheckAutoTierListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CheckAutoTierEvent  $event
     * @return void
     */
    public function handle(CheckAutoTierEvent $event)
    {
        $affiliate = $event->affiliate;
        $this->handleAutoTier($affiliate);
    }
    private function handleAutoTier($affiliate)
    {
        $autoTier = AffiliateTier::where('shop_id', $affiliate->shop_id)
            ->where('status', 1)
            ->first();
        $shopSetting = ShopSetting::where('shop_id', $affiliate->shop_id)->select('timezone')->first();
        if ($autoTier) {
            $levels = $autoTier->data_levels;
            foreach ($levels as $k => $l) {
                if ($affiliate->group_id == $l['program_id']) {
                    if (isset($levels[$k + 1])) {
                        if ($autoTier->level_condition == AffiliateTier::TOTAL_CONVERSION_VALUE) {
                            $condition = Conversion::where('affiliate_id', $affiliate->id)
                                ->where('status', 1)
                                ->sum('total');
                        } else if ($autoTier->level_condition == AffiliateTier::TOTAL_EARNED_COMMISSION_VALUE) {
                            $condition = Conversion::where('affiliate_id', $affiliate->id)
                                ->where('status', 1)
                                ->sum('commission');
                        } else if ($autoTier->level_condition == AffiliateTier::MONTHLY_SALES_AUTO_DOWNGRADE) {
                            $from = Carbon::now()->tz( $shopSetting->timezone )->startOfMonth()->setTimezone('UTC');
                            $to = Carbon::now();
                            $condition = Conversion::where('affiliate_id', $affiliate->id)->whereBetween('created_at', [$from, $to])
                                ->where('status', 1)
                                ->sum('total');
                        } else if ($autoTier->level_condition == AffiliateTier::MONTHLY_ORDERS_AUTO_DOWNGRADE) {
                            $from = Carbon::now()->tz( $shopSetting->timezone )->startOfMonth()->setTimezone('UTC');
                            $to = Carbon::now();
                            $condition = Conversion::where('affiliate_id', $affiliate->id)->whereBetween('created_at', [$from, $to])
                                ->where('status', 1)
                                ->count();
                                
                        } else {
                            $condition = Conversion::where('affiliate_id', $affiliate->id)
                                ->whereNull('refund_id')
                                ->count();
                        }
                        for ($i = $k + 1; $i < count($levels); $i++) {
                            if ($condition >= $levels[$i]['condition_amount']) {
                                $newProgram = $levels[$i]['program_id'];
                            } else {
                                break;
                            }
                        }
                        if (isset($newProgram)) {
                            $affiliate->group_id = $newProgram;
                            $affiliate->save();
                        }
                    }
                    break;
                }
            }
        }
    }
}
