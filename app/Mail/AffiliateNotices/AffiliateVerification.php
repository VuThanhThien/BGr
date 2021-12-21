<?php

namespace App\Mail\AffiliateNotices;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Mail\AffiliateNotices\Notice;

class AffiliateVerification extends Notice
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content = '<p style="color: red;">hahahahahaha</p>';
        return $this->from('support@bixgrow.com')
                    ->view('mails.merchants.new_affiliate')
                    ->with(['content' => $content]);
    }
}
