<?php

namespace App\Mail\AffiliateNotices;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class Notice extends Mailable
{
    
    public $sender;

    public function replacesTags($replaces, $content) 
    {
        foreach($replaces as $k=>$t) {
            $content = str_replace('{'.$k.'}', $t , $content);
        }

        return $content;
    }

    public function setSender($shopSetting)
    {
        $this->sender = config('mail.from.address');
        if($shopSetting->is_verified_from_email && $shopSetting->from_contact_email) {
            $this->sender = $shopSetting->from_contact_email;
        }
    }

    public function setLogoAndBrandName($logo, $brandName)
    {
        
        return [
            'logo'=> $logo,
            'brand_name' => $brandName
        ];
    }


    
}
