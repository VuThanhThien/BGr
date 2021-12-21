<?php

namespace App\Services;

use App\Models\MailOutbox;

Class MailhookService
{
    public function updateMailOutbox($data)
    {
        $message = json_decode(json_decode($data)->Message);
        $replaces = [
            '<' =>'',
            '>' =>'',
        ];
        $eventType = $message->eventType;
        $arrHeaderMessage = $message->mail->headers;
        foreach($arrHeaderMessage as $headerMessage)
        {
            if($headerMessage->name = 'Message-ID')
            {
                $messageId = $this->replacesMessageId($replaces,$headerMessage->value);
                break;
            }
        }
        if(isset($messageId))
        {
            $mailOutbox = MailOutbox::where('message_id',$messageId)->first();
            if($mailOutbox)
            {
                $mailOutbox->status = $eventType;
                if($eventType == 'Bounce')
                {
                    $mailOutbox->error_message = json_encode($message->bounce->bouncedRecipients); 
                }               
                $mailOutbox->save();
            }
        }
        return true;
    }

    private function replacesMessageId($replaces,$message)
    {
        foreach($replaces as $key => $value)
        {
            $message = str_replace($key,$value,$message);
        }
        return $message;
    }
}
