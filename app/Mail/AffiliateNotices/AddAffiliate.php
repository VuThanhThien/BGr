<?php

namespace App\Mail\AffiliateNotices;

use App\Models\EmailTemplate;
use App\Models\Group;
use App\Models\ShopSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddAffiliate extends Notice
{
    use Queueable, SerializesModels;
    public $affiliate;
    public $password;
    public $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($affiliate,$password)
    {
        $this->affiliate=$affiliate;
        $this->password=$password;
        $this->type = 'new-affiliate';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = EmailTemplate::where('shop_id',$this->affiliate->shop_id)->where('slug','invitation_affiliate')->first();
        if(!$template)
        {
            $template = config('myconfig.affiliatenotices.invitation_affiliate');
            $template['content'] = view($template['content'])->render();
        }
        if( !is_array($template) ) {
            $template = $template->toArray();
        }
        if($template['status'])
        {
            $shopSetting=ShopSetting::where('shop_id',$this->affiliate->shop_id)->select('shop_id','sub_domain','brand_name','from_contact_email', 'is_verified_from_email','default_affiliate_link')
            ->with('info:shop_id,money_format')
            ->first();
            $group = Group::findOrFail($this->affiliate->group_id);
            $this->setSender($shopSetting);
            $this->withSwiftMessage(function ($message) {
                $headers = $message->getHeaders();
                $headers->addTextHeader('X-SES-CONFIGURATION-SET', config('myconfig.constants.textheader'));       
            });
            $replaces = [
                'brand_name' =>ucfirst($shopSetting->brand_name),
                'first_name' => $this->affiliate->first_name,
                'affiliate_link' => $this->generateLinkAffiliate($shopSetting->default_affiliate_link).$group->id.':'.$this->affiliate->hash_code,
                'commission_amount' => formatCommissionAmount($group->commission_type, $group->commission_amount,$shopSetting->info->money_format),
                'commission_type' => genTextCommissionType($group->commission_type),
                'affiliate_login_url'=> 'https://' . $shopSetting->sub_domain . '.' . config('endpoint.main_domain') . '#/login',
                'password'=>$this->password
            ];
            $this->content = $this->replacesTags($replaces, $template['content']);
            $this->subject = $template['subject'];
            return $this->from($this->sender)
            ->view('mails.container')
            ->with(['content' => $this->content]);
        }
      
}
    protected function generateLinkAffiliate($link)
    {
        if($link)
        {
            $arrLinkSplit = explode('?',$link);
            if(count($arrLinkSplit)>1 && $arrLinkSplit[1] !== '')
            {
                return $link . "&bg_ref=";
            }
            else{
                return $link . "?bg_ref=";
            }
        }
        else{
            return '';
        }
    }
}
