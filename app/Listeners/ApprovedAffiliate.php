<?php

namespace App\Listeners;

use App\Events\ApprovedAffiliateEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\NoticeAffiliate;

class ApprovedAffiliate
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
     * @param  ApprovedAffiliateEvent  $event
     * @return void
     */
    public function handle(ApprovedAffiliateEvent $event)
    {
        $this->sendNoticeToAffiliate($event->affiliate, 'approved_affiliate');
    }
}
