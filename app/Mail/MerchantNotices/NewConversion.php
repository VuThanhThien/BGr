<?php

namespace App\Mail\MerchantNotices;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ShopSetting;

class NewConversion extends Mailable
{
    use Queueable, SerializesModels;

    public $affiliate;
    public $conversion;
    public $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($affiliate, $conversion)
    {
        $this->affiliate = $affiliate;
        $this->conversion = $conversion;
        $this->type = 'merchant';
    }


    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $shopSetting = ShopSetting::where('shop_settings.shop_id', $this->affiliate->shop_id)
        ->join('shop_infos', 'shop_settings.shop_id', '=', 'shop_infos.shop_id')
        ->select('shop_settings.contact_email', 'shop_settings.contact_name', 'shop_infos.money_format')
        ->first();
        $content = view('mails.merchants.new_conversion')->render();
        $sender = config('mail.from.address');
        $replaces = [
            'merchant_name'=> $shopSetting->contact_name,
            'order_number' => $this->conversion->order_name,
            'total_sales' => currency_format($shopSetting->money_format, $this->conversion->total),
            'commission' => currency_format($shopSetting->money_format, $this->conversion->commission),
            'affiliate' => $this->affiliate->full_name,
        ];
        
        $this->content = $this->replacesTags($replaces, $content);
        $this->subject = 'Big news! You have a new referral sale';
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
