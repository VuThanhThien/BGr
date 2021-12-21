<?php

namespace App\Listeners;

use App\Events\NewAffiliateEvent;
use App\Models\MailOutbox;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class LogSendingMessage
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

   
    public function handle($event)
    {
        // info($event->message->getSubject());
        // info($event->data['affiliate']);
        // $message =json_decode((json_encode($event)));
        // info($message->affiliate);
        // info('MESSAGE ID: ' . $event->message->getId());
        if(isset($event->data['type']))
        {
            if($event->data['type']!='merchant')
            {
                $mailOutBox = new MailOutbox;
                $mailOutBox->shop_id = $event->data['affiliate']['shop_id'];
                $mailOutBox->from = $event->data['sender'];
                $mailOutBox->to = $event->data['affiliate']['email'];
                $mailOutBox->subject = $event->message->getSubject();
                $mailOutBox->status = 'Pending';
                $mailOutBox->message_id = $event->message->getId();
                $mailOutBox->error_message = null;
                $mailOutBox->affiliate_id = $event->data['affiliate']['id'];
                $mailOutBox->email_type = $event->data['type'];
                $mailOutBox->save(); 
            }          
        }
    }
}
