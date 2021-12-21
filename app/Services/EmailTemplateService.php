<?php

namespace App\Services;

use App\Jobs\BulkEmailJob;
use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\ShopInfo;
use App\Models\Conversion;
use Illuminate\Support\Facades\Auth;
use App\Models\EmailTemplate;
use App\Models\MailOutbox;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

Class EmailTemplateService
{

    public function getEmailTemplate($slug)
    {
        $shopId=auth()->user()->shop_id;
        $emailTempalte = EmailTemplate::where('slug',$slug)->where('shop_id',$shopId)->first();
        if(!$emailTempalte){
            $emailTempalte = config('myconfig.affiliatenotices.'.$slug);
            $emailTempalte['content'] = view($emailTempalte['content'])->render();
        }

        return $emailTempalte;
    }

    public function store($data){
        $shopId=auth()->user()->shop_id;
        $emailTemplate=new EmailTemplate();
        $emailTemplate->shop_id=$shopId;
        $emailTemplate->name=config('myconfig.affiliatenotices.'.$data['slug'].'.name');
        $emailTemplate->subject = $data['subject'];
        $emailTemplate->content = $data['content'];
        $emailTemplate->status = $data['status'];
        $emailTemplate->slug = $data['slug'];
        $emailTemplate->save();
        return $emailTemplate;
    }
    public function createOrUpdate($data)
    {
        $shopId = auth()->user()->shop_id;
        $emailTemplate = EmailTemplate::where('slug', $data['slug'])->where('shop_id', $shopId)->first();
        if($emailTemplate) {
            $emailTemplate->shop_id=$shopId;
            $emailTemplate->subject=$data['subject'];
            $emailTemplate->content=$data['content'];
            $emailTemplate->status=$data['status'];
            $emailTemplate->slug=$data['slug'];
            $emailTemplate->save();
        }else{
            $emailTemplate = $this->store($data);
        }

        return $emailTemplate;
    }
    public function getEmailTemplateStatusByShopId()
    {
        $shopId = auth()->user()->shop_id;
        $emailTemplateStatusByShopId=EmailTemplate::where('shop_id',$shopId)->select('status','slug')->get();
        return $emailTemplateStatusByShopId;
    }
    public function changeStatusEmailTemplate($data)
    {
        $shopId = auth()->user()->shop_id;
        $emailTempalte = EmailTemplate::where('shop_id',$shopId)->where('slug',$data['slug'])->first();
        if($emailTempalte){
            $emailTempalte->status=$data['status'];
            $emailTempalte->save();
        }else{
            $defaultTemplate = config('myconfig.affiliatenotices.'.$data['slug']);
            $emailTemplate=new EmailTemplate();
            $emailTemplate->shop_id = $shopId;
            $emailTemplate->name = $defaultTemplate['name'];
            $emailTemplate->subject = $defaultTemplate['subject'];
            $emailTemplate->content = view($defaultTemplate['content'])->render();
            $emailTemplate->status = 0;
            $emailTemplate->slug = $defaultTemplate['slug'];
            $emailTemplate->save();
        }
        return $emailTempalte;
    }

    public function bulkEmail($data){
        $shopId = auth()->user()->shop_id;
        if($data['predefinedSelected'] == 'specify')
        {
            if(count($data['affiliates']))
            {
                $affiliates = Affiliate::whereIn('id',$data['affiliates'])->get();
                foreach($affiliates as $affiliate)
                {
                    Mail::to($affiliate)->queue((new \App\Mail\AffiliateNotices\BulkEmail($affiliate,$data))->onQueue('emails'));
                }
            }

        }
        else{
           dispatch(new BulkEmailJob($data,$shopId))->delay(Carbon::now()->addSeconds(1));
        }
        return true;
    }

    public function getMailOutbox($data){
        $shopId = auth()->user()->shop_id;
        $conditions = [['mail_outbox.shop_id',$shopId]];
        if(isset($data['affiliate']))
        {
            $conditions[] = ['affiliate_id',$data['affiliate']];
        }
        if(isset($data['status']))
        {
            $conditions[] = ['status',$data['status']];
        }
        $mailOutBox = MailOutbox::where($conditions);
        if(isset($data['start_date']))
        {
            $startDate = Carbon::createFromTimestamp(intval($data['start_date']))->toDateTimeString();
            $endDate = Carbon::createFromTimestamp(intval($data['end_date']))->toDateTimeString();
            $mailOutBox = $mailOutBox->whereBetween('created_at',[$startDate,$endDate]);
        }
        $mailOutBox = $mailOutBox->with(['affiliate:id,first_name,last_name'])->orderBy($data['sort_field'],$data['sort_direction'])
        ->paginate($data['paginate']);
        return $mailOutBox;

    }

    public function uploadEmailImage($data, $file)
    {
        $fileNameHash = time() . '_' . generateRandomString(5);
        $filePath = $file->storePubliclyAs('Uploads', $fileNameHash,'s3','public');
        return [
            "location" => "https://d2xrtfsb9f45pw.cloudfront.net/".$filePath
        ];
    }

}
