<?php

namespace App\Listeners;

use App\Events\ApprovedConversionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\NoticeAffiliate;

class ApprovedConversion
{
    use NoticeAffiliate;
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
     * @param  ApprovedConversionEvent  $event
     * @return void
     */
    public function handle(ApprovedConversionEvent $event)
    {
        $affiliate = \App\Models\Affiliate::where('id', $event->conversion->affiliate_id)->select('id', 'last_name', 'first_name','email','shop_id','group_id')->first();
        $this->sendNoticeToAffiliate($affiliate, 'approved_conversion', ['order_number' => $event->conversion->order_name]);
        event(new \App\Events\CheckAutoTierEvent($affiliate));
    }
}
