<?php

namespace App\Listeners;

use App\Events\DeniedConversionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\NoticeAffiliate;

class DeniedConversion
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
     * @param  DeniedConversionEvent  $event
     * @return void
     */
    public function handle(DeniedConversionEvent $event)
    {
        $affiliate = \App\Models\Affiliate::where('id', $event->conversion->affiliate_id)->select('id', 'last_name', 'first_name','email','shop_id')->first();
        $this->sendNoticeToAffiliate($affiliate, 'denied_conversion', ['order_number' => $event->conversion->order_name]);
    }
}
