<?php

namespace App\Mail\MerchantNotices;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ShopSetting;

class NewAffiliate extends Mailable
{
    use Queueable, SerializesModels;

    public $affiliate;
    public $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($affiliate)
    {
        $this->affiliate = $affiliate;
        $this->type = 'merchant';
    }


    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $shopSetting = ShopSetting::where('shop_id', $this->affiliate->shop_id)
        ->select('logo', 'brand_name', 'contact_email', 'contact_name')
        ->first();
        $content = view('mails.merchants.new_affiliate')->render();
        $sender = config('mail.from.address');
        $replaces = [
            'merchant_name' => $shopSetting->contact_name,
            'full_name'=> $this->affiliate->full_name,
            'email' => $this->affiliate->email,
            'source' => $this->affiliate->source,
        ];
        $this->content = $this->replacesTags($replaces, $content);
        $this->subject = 'A new affiliate has registered';
        return $this->from($sender)
                    ->view('mails.container')
                    ->with(['content' => $this->content]);
    }

    public function replacesTags($replaces, $content) 
    {
        foreach($replaces as $k=>$t) {
            $content = str_replace('{'.$k.'}', $t , $content);
        }

        return $content;
    }
}
