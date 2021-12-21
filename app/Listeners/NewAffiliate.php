<?php

namespace App\Listeners;

use App\Events\NewAffiliateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\Mail;

class NewAffiliate
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
     * @param  NewAffiliateEvent  $event
     * @return void
     */
    public function handle(NewAffiliateEvent $event)
    {
        $affiliate = $event->affiliate;
        $shopSetting = ShopSetting::where('shop_id', $affiliate->shop_id)->first();
        if($shopSetting->notify_self_new_registrations)
        {
            Mail::to($shopSetting->contact_email)->queue((new \App\Mail\MerchantNotices\NewAffiliate($affiliate))->onQueue('emails'));
        }
       
    }
}
