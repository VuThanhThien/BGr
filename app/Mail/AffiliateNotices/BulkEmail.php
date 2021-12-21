<?php

namespace App\Mail\AffiliateNotices;

use App\Models\ShopSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkEmail extends Notice
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public $affiliate;
    public $type;
    public function __construct($affiliate,$data)
    {
        $this->data = $data;
        $this->affiliate = $affiliate;
        $this->type = 'bulk_email';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $shopSetting = ShopSetting::where('shop_id',$this->affiliate->shop_id)->select('brand_name', 'from_contact_email', 'is_verified_from_email')->first();
        $replaces = [
            'first_name' => $this->affiliate->first_name,
            'brand_name' => $shopSetting->brand_name
        ];
        $this->content = $this->replacesTags($replaces, $this->data['content']);
        $this->subject = $this->data['subject'];
        $this->setSender($shopSetting);
        $this->withSwiftMessage(function ($message) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('X-SES-CONFIGURATION-SET', config('myconfig.constants.textheader'));       
        });
        return $this->from($this->sender)
        ->view('mails.container')
        ->with(['content' => $this->content]);
    }
}
