<?php

namespace App\Listeners;

use App\Events\CommissionPayoutEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\NoticeAffiliate;

class CommissionPayout
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
     * @param  CommissionPayoutEvent  $event
     * @return void
     */
    public function handle(CommissionPayoutEvent $event)
    {
        $this->sendNoticeToAffiliate($event->affiliate, 'commission_payout', ['payment' => $event->payment]);
    }
}
