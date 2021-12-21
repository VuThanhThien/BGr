<?php

namespace App\Listeners;

use App\Events\AddAffiliateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class AddAffiliate
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
     * @param  AddAffiliateEvent  $event
     * @return void
     */
    public function handle(AddAffiliateEvent $event)
    {
        $affiliate = $event->affiliate;
        $password = $event->password;
        Mail::to($affiliate)->queue((new \App\Mail\AffiliateNotices\AddAffiliate($affiliate,$password))->onQueue('emails'));
    }
}
