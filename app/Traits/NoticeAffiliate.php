<?php

namespace App\Traits;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;

trait NoticeAffiliate{

    protected function sendNoticeToAffiliate($affiliate, $type, $ext = [])
	{
        
		$template = EmailTemplate::where('shop_id', $affiliate->shop_id)->where('slug', $type)->first();
        if(!$template) {
            $template = config('myconfig.affiliatenotices.'.$type);
            $template['content'] = view($template['content'])->render();
        }

        if( !is_array($template) ) {
            $template = $template->toArray();
        }
        if($template['status']) {
            if($type == 'affiliate_review'){
                $mailAble = new \App\Mail\AffiliateNotices\AffiliateReview($affiliate, $template);
            }else if($type == 'approved_affiliate'){
                $mailAble = new \App\Mail\AffiliateNotices\ApprovedAffiliate($affiliate, $template);
            }else if($type == 'denied_affiliate'){
                $mailAble = new \App\Mail\AffiliateNotices\DeniedAffiliate($affiliate, $template);
            }else if($type == 'affiliate_verification'){
                $mailAble = new \App\Mail\AffiliateNotices\AffiliateVerification($affiliate);
            }else if($type == 'new_conversion'){
                $mailAble = new \App\Mail\AffiliateNotices\NewConversion($affiliate, $template, $ext);
            }else if($type == 'approved_conversion'){
                $mailAble = new \App\Mail\AffiliateNotices\ApprovedConversion($affiliate, $template, $ext);
            }else if($type == 'denied_conversion'){
                $mailAble = new \App\Mail\AffiliateNotices\DeniedConversion($affiliate, $template, $ext);
            }else if($type == 'commission_payout'){
                $mailAble = new \App\Mail\AffiliateNotices\CommissionPayout($affiliate, $template, $ext);
            }
            

            $mailAble = $mailAble->onQueue('emails');
            Mail::to($affiliate)->queue($mailAble);
        }
	}

	

}
