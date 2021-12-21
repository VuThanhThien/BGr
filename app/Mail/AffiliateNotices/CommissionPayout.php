<?php

namespace App\Mail\AffiliateNotices;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Mail\AffiliateNotices\Notice;
use App\Models\ShopSetting;

class CommissionPayout extends Notice
{
    use Queueable, SerializesModels;
    public $affiliate;
    public $template;
    public $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($affiliate, $template)
    {
        $this->affiliate = $affiliate;
        $this->template = $template;
        $this->type = 'commission_payout';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $shopSetting = ShopSetting::where('shop_id', $this->affiliate->shop_id)->first();

        $replaces = [
            'first_name' => $this->affiliate->first_name,
        ];
        $replaces = array_merge($replaces, $this->setLogoAndBrandName($shopSetting->logo, $shopSetting->brand_name));
        $this->content = $this->replacesTags($replaces, $this->template['content']);
        $this->subject = $this->template['subject'];
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
