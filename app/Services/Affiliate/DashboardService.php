<?php

namespace App\Services\Affiliate;

use App\Models\Affiliate;
use App\Models\Click;
use App\Models\Group;
use App\Models\Shop;
use App\Models\Conversion;
use App\Models\PaymentMethod;
use App\Models\ShopPaymentMethod;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Class DashboardService
{
    public function getInitData()
    {
        $responseData = [];
        $user = auth('affiliate-api')->user();
        $shopId = $user->shop_id;
        $paymentMethodAvailable = ShopPaymentMethod::where('shop_payment_methods.shop_id', $shopId)
        ->join('payment_methods', 'payment_methods.id', '=', 'shop_payment_methods.method_id')
        ->select('payment_methods.*')
        ->get();
        $affiliateSetting = $user->setting()->first();
        $responseData['payment_method_available'] = $paymentMethodAvailable;
        $responseData['affiliate_setting'] = $affiliateSetting;
        return $responseData;
    }
    public function getPerformance($data)
    {
        $affiliateId=auth('affiliate-api')->user()->id;
        $startDate = \Carbon\Carbon::createFromTimestamp(intval($data['start_date']))->toDateTimeString();
        $endDate= \Carbon\Carbon::createFromTimestamp(intval($data['end_date']))->toDateTimeString();
        if($data['type']=='clicks')
        {
            $clicks=Click::whereHas('affiliate',function($query) use($affiliateId){
                $query->where('id',$affiliateId);
            })->whereBetween('date',[$startDate,$endDate])->select([DB::raw('sum(count) as clicks'),
            DB::raw('date as times')])->groupBy('date')->get();
            return [
                'line'=>  $clicks,
                'type'=>'clicks'
              ];
        }
        else{
            $conversion=Conversion::whereHas('affiliate',function($query) use($affiliateId){
                $query->where('id',$affiliateId);
            })->whereBetween('created_at',[$startDate,$endDate]);
            if($data['type']=='orders')
            {
                $orders=$conversion->select([DB::raw('count(*) as orders'),DB::raw('DATE(created_at) as times')])
                ->groupBy(DB::raw('times'))->get();
                return [
                    'line'=>  $orders,
                    'type'=>'orders'
                  ];
            }
            else{
                $sales= $conversion->select([DB::raw('sum(total) as sales'),DB::raw('DATE(created_at) as times')])
                ->groupBy(DB::raw('times'))->get();
                return [
                    'line'=> $sales,
                    'type'=>'sales'
                ];
            }
        }

    }
    public function getLogoShop($origin)
    {
        $subdomain = getSubdomainFromUrl($origin);
        $setting = ShopSetting::where('sub_domain', $subdomain)->whereHas('shop', function ($query) {
            $query->whereNotNull('access_token');
        })->select('logo','path_name')->first();
        if($setting)
        {
           if($setting->logo) 
           {
               return [
                   'status' => true,
                   'logo' => $setting->logo
               ];
           }
           else{
            return [
                'status' => true,
                'logo' => null
            ];
           }
        }
        return [
            'status' => false,
            'logo' =>null
        ];
    }
}
