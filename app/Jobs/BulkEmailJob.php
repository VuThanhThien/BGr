<?php

namespace App\Jobs;

use App\Models\Affiliate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BulkEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public $shopId;
    public function __construct($data,$shopId)
    {
        $this->data = $data;
        $this->shopId = $shopId;    
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->data['predefinedSelected'] == 'all')
        {
            Affiliate::where('shop_id', $this->shopId)->chunk(100,function($affiliates) {
                foreach($affiliates as $affiliate)
                {
                    Mail::to($affiliate)->queue((new \App\Mail\AffiliateNotices\BulkEmail($affiliate,$this->data))->onQueue('emails'));
                }    
            });
        }
        if($this->data['predefinedSelected'] == 'program')
        {
            Affiliate::where('shop_id', $this->shopId)->where('group_id',$this->data['program'])->chunk(100,function($affiliates) {
                foreach($affiliates as $affiliate)
                {
                    Mail::to($affiliate)->queue((new \App\Mail\AffiliateNotices\BulkEmail($affiliate,$this->data))->onQueue('emails'));
                }    
            });
        }
        if($this->data['predefinedSelected'] == 'approved')
        {
            Affiliate::where('shop_id', $this->shopId)->where('status',1)->chunk(100,function($affiliates) {
                foreach($affiliates as $affiliate)
                {
                    Mail::to($affiliate)->queue((new \App\Mail\AffiliateNotices\BulkEmail($affiliate,$this->data))->onQueue('emails'));
                }    
            });
        }
        if($this->data['predefinedSelected'] == 'denied')
        {
            Affiliate::where('shop_id', $this->shopId)->where('status',3)->chunk(100,function($affiliates) {
                foreach($affiliates as $affiliate)
                {

                    Mail::to($affiliate)->queue((new \App\Mail\AffiliateNotices\BulkEmail($affiliate,$this->data))->onQueue('emails'));
                }    
            });
        }
    }
}
