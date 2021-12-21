<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\Http;

class IntegrationService
{
    
    public function toggleSyncKlaviyoApprovedAffiliate()
    {
        $shopId = auth()->user()->shop_id;
        $shopSettings = ShopSetting::where('shop_id', $shopId)->first();
        $shopSettings->sync_klaviyo_approved_affiliate = !$shopSettings->sync_klaviyo_approved_affiliate;
        $shopSettings->save();
        return [
            'sync_klaviyo_approved_affiliate' => $shopSettings->sync_klaviyo_approved_affiliate ? 1 : 0
        ];
    }

    public function getListKlaviyo($data)
    {
        $klaviyoApiServices = new KlaviyoApiService($data['klaviyo_api_key']);
        return $klaviyoApiServices->getListKlaviyo();
    }

    public function saveSyncKlaviyo($data)
    {
        try{
            $shopId = auth()->user()->shop_id;
            $shopSettings = ShopSetting::where('shop_id', $shopId)->first();
            $shopSettings->klaviyo = $data['klaviyo'];
            $shopSettings->klaviyo_sync_enabled = $data['klaviyo_sync_enabled'];
            $shopSettings->save();
            if ($shopSettings->klaviyo_sync_enabled) {
                $klaviyoApiServices = new KlaviyoApiService($shopSettings->klaviyo['klaviyo_api_key']);
                if (!$shopSettings->sync_klaviyo_approved_affiliate) {
                    $affiliates = Affiliate::where('shop_id', $shopId)->with('coupons')->chunk(100, function ($affiliates) use ($shopSettings, $klaviyoApiServices) {
                        $profiles = $this->makeProfilesKlaviyo($affiliates);
                        $klaviyoApiServices->addMemberToKlaviyo($profiles, $shopSettings->klaviyo['klaviyo_list']['list_id']);
                    });
                } else {
                    $affiliates = Affiliate::where('shop_id', $shopId)->where('status', 1)->with('coupons')->chunk(100, function ($affiliates) use ($shopSettings, $klaviyoApiServices) {
                        $profiles = $this->makeProfilesKlaviyo($affiliates);
                        $klaviyoApiServices->addMemberToKlaviyo($profiles, $shopSettings->klaviyo['klaviyo_list']['list_id']);
                    });
                   $affiliates = Affiliate::where('shop_id', $shopId)->where('status',3)->chunk(100,function($affiliates) use($shopSettings, $klaviyoApiServices){
                        $profiles = $this->makeDeleteProfilesKlaviyo($affiliates);
                        $klaviyoApiServices->deleteMemberKlaviyo($profiles, $shopSettings->klaviyo['klaviyo_list']['list_id']);
                   }) ;
                }
            }
        }
        catch(\Exception $ex)
        {
            report($ex);
        }
        return [
            'status' => 'ok',
            'message' => "Sync Completed"
        ];
    }

    public function syncKlaviyo()
    {
        try
        {
            $shopId = auth()->user()->shop_id;
            $shopSettings = ShopSetting::where('shop_id',$shopId)->select('klaviyo','klaviyo_sync_enabled','sync_klaviyo_approved_affiliate')->first();
            if($shopSettings->klaviyo_sync_enabled)
            {
                $klaviyoApiServices = new KlaviyoApiService($shopSettings->klaviyo['klaviyo_api_key']);
                if (!$shopSettings->sync_klaviyo_approved_affiliate) {
                    $affiliates = Affiliate::where('shop_id', $shopId)->with('coupons')->chunk(100, function ($affiliates) use ($shopSettings,$klaviyoApiServices) {
                        $profiles = $this->makeProfilesKlaviyo($affiliates);
                        $klaviyoApiServices->addMemberToKlaviyo($profiles,$shopSettings->klaviyo['klaviyo_list']['list_id']);
                    });
                } else {
                    $affiliates = Affiliate::where('shop_id', $shopId)->where('status', 1)->with('coupons')->chunk(100, function ($affiliates) use ($shopSettings,$klaviyoApiServices) {
                        $profiles = $this->makeProfilesKlaviyo($affiliates);
                        $klaviyoApiServices->addMemberToKlaviyo($profiles, $shopSettings->klaviyo['klaviyo_list']['list_id']);
                    });
                   $affiliates = Affiliate::where('shop_id', $shopId)->where('status',3)->chunk(100,function($affiliates) use($shopSettings,$klaviyoApiServices){
                        $profiles = $this->makeDeleteProfilesKlaviyo($affiliates);
                        $klaviyoApiServices->deleteMemberKlaviyo($profiles, $shopSettings->klaviyo['klaviyo_list']['list_id']);
                   }) ;
                }
            }
        }
        catch(\Exception $ex)
        {
            report($ex);
        }
        return [
            'status' => 'ok',
            'message' => "Sync Completed"
        ];
    }

    public function toggleKlaviyoSyncEnabled()
    {
        $shopId = auth()->user()->shop_id;
        $shopSettings = ShopSetting::where('shop_id', $shopId)->first();
        $shopSettings->klaviyo_sync_enabled = !$shopSettings->klaviyo_sync_enabled;
        $shopSettings->save();
        return [
            'klaviyo_sync_enabled' => $shopSettings->klaviyo_sync_enabled ? 1 : 0
        ];
    }

    public function makeProfilesKlaviyo($affiliates)
    {
        $profiles = [];
        foreach ($affiliates as $aff) {
            $pro = [
                "email" => $aff->email,
                "first_name" => $aff->first_name,
                "last_name" => $aff->last_name,
                "name" => $aff->first_name . ' ' . $aff->last_name,
                "referral_code" => $aff->group_id . ':' . $aff->hash_code,
            ];
            if (isset($aff->facebook)) {
                $pro['facebook'] = $aff->facebook;
            }
            if (isset($aff->instagram)) {
                $pro['instagram'] = $aff->instagram;
            }
            if (isset($aff->youtube)) {
                $pro['youtube'] = $aff->youtube;
            }
            if (isset($aff->twitter)) {
                $pro['twitter'] = $aff->twitter;
            }
            if ($aff->coupons) {
                $coupons = [];
                foreach ($aff->coupons as $cou) {
                    $coupons[] = $cou->code;
                }
                if (!empty($coupons)) {
                    $pro['coupon'] = implode(",", $coupons);
                }
            }
            $profiles[] = $pro;
        }
        return $profiles;
    }

    public function makeDeleteProfilesKlaviyo($affiliates)
    {
        $profiles = [];
        foreach ($affiliates as $aff)
        {
            $profiles[] = $aff->email;
        }
        return $profiles;
    }
 
}
