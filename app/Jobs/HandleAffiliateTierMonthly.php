<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AffiliateTier;
use App\Models\Affiliate;

class HandleAffiliateTierMonthly implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timezones;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $timezones)
    {
        $this->timezones = $timezones;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shops = AffiliateTier::where(function($query){
            $query->where('affiliate_tier.level_condition', AffiliateTier::MONTHLY_SALES_AUTO_DOWNGRADE);
            $query->orWhere('affiliate_tier.level_condition', AffiliateTier::MONTHLY_ORDERS_AUTO_DOWNGRADE);
        })
        ->join('shop_settings', 'shop_settings.shop_id', '=', 'affiliate_tier.shop_id')
        ->whereIn('shop_settings.timezone', ['Asia/Phnom_Penh'])
        ->where('affiliate_tier.status', 1)
        ->select('affiliate_tier.shop_id as id', 'affiliate_tier.data_levels')
        ->chunkById(100, function ($shops) {
            
            foreach($shops as $s) {
                
                $dataLevel = $s->data_levels;
                $programIds = [];
                foreach($dataLevel as $d) {
                    $programIds[] = $d['program_id'];
                }
                Affiliate::where('shop_id', $s->id)->whereIn('group_id', $programIds)->update(['group_id' => $dataLevel[0]['program_id']]);
            }
        });

    }

    
}
