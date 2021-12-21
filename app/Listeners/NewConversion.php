<?php

namespace App\Listeners;

use App\Events\NewConversionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\NoticeAffiliate;
use Illuminate\Support\Facades\Mail;
use App\Models\ShopSetting;

class NewConversion
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
     * @param  NewConversionEvent  $event
     * @return void
     */
    public function handle(NewConversionEvent $event)
    {
        //send tonification to affiliate
        $this->sendNoticeToAffiliate($event->affiliate, 'new_conversion', ['conversion_id'=>$event->conversion->id]);

        //send notification to merchant
        $shopSetting = ShopSetting::where('shop_id', $event->affiliate->shop_id)->first();
        if($shopSetting->notify_self_new_order)
        {
            Mail::to($shopSetting->contact_email)->queue((new \App\Mail\MerchantNotices\NewConversion( $event->affiliate, $event->conversion ))->onQueue('emails'));
        }    
        if($event->conversion->status ==1)
        {
            event(new \App\Events\CheckAutoTierEvent($event->affiliate));
        } 
    }
}
