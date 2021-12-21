<?php

namespace App\Listeners;

use App\Events\DeniedAffiliateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\NoticeAffiliate;

class DeniedAffiliate
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
     * @param  DeniedAffiliateEvent  $event
     * @return void
     */
    public function handle(DeniedAffiliateEvent $event)
    {
        $this->sendNoticeToAffiliate($event->affiliate, 'denied_affiliate');
    }
}
