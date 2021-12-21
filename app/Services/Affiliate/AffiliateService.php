<?php

namespace App\Services\Affiliate;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\Conversion;
use Illuminate\Support\Facades\Auth;
use DB;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Redirect;
use Illuminate\Support\Facades\Validator;

class AffiliateService {
    public function getAffiliate($id){
        $affiliate=Affiliate::where('id',$id)->first();
        return $affiliate;
    }
    public function getNetworkStatistics()
    {
        $affiliate=auth('affiliate-api')->user();
       $group = Group::where('id', $affiliate->group_id)->select('id', 'mlm_tree')->first();
        $mlmTree = $group->mlm_tree;
       $levels = [];
       $dataLevel = [];
       $earnings = 0;
       $totalSignups = 0;

       // $networkLink = url('/').'/'.$domain.'/register?ref='.$affiliate->hash_code;

       if ($mlmTree) {
           for ($i = 1; $i <= count($mlmTree); $i++) {
               $levels[] = $i;
           }
           $infos = Conversion::where('affiliate_id', $affiliate->id)
               ->whereIn('level', $levels)
               ->select('level', DB::raw('SUM(total) as total_revenue'), DB::raw('COUNT(*) as total_conversion'), DB::raw('SUM(commission) as total_commission'))
               ->groupBy('level')
               ->orderBy('level')
               ->get();
           $ids = [$affiliate->id];

           for ($i = 0; $i < count($mlmTree); $i++) {
               $name = 'Level ' . ($i + 1);
               $ids = Affiliate::whereIn('parent_id', $ids)->pluck('id')->toArray();
               $dataLevel[] = ['level' => $name, 'total_signup' => count($ids), 'statistics' => $this->searchLevel($infos, $i + 1)];
           }
           
       }


       foreach ($dataLevel as $v) {
           if ($v['statistics']) {
               $earnings = $earnings + $v['statistics']->total_commission;
           }

           $totalSignups = $totalSignups + $v['total_signup'];
       }

       return [
            'totalCommission' => $earnings,
            'totalDownline' => $totalSignups,
            'levelInfos' => $dataLevel
       ];
    }
    public function searchLevel($data, $level)
    {
        foreach ($data as $d) {
            if ($d['level'] == $level) return $d;
        }
        return null;
    }
    public function setCustomAffLink($data)
    {
        $affiliate=auth('affiliate-api')->user();
        $shop=Shop::findOrFail($affiliate->shop_id);
        $result=Validator::make($data,
        [
            'path' =>[
                'required',
                function ($attribute, $value, $fail) use ($affiliate) {
                    $affiliatesCustomLinkExist = Affiliate::where([
                        'id' => $affiliate->id,
                        'affiliates_custom_link' =>'/'. $value
                    ])->get();
                    if ($affiliatesCustomLinkExist->count() != 0) {
                        $fail('Path has already been taken');

                    }
                }
            ]
            ]);
        $result->validate();
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $redirectApi=new Redirect($clientApi);
        $params=[
            "redirect"=> [
                "path"=>$data['path'],
                "target"=>$data['target']
              ]
            ];
        $redirectApi=$redirectApi->create($params);
        if(isset($redirectApi['errors']))
        {
            return [
                'status'=>false,
                'message'=>$redirectApi['errors']['path']['0'],
                'path'=>''
            ];
        }
        $affiliate->affiliates_custom_link = $redirectApi['redirect']['path'];
        $affiliate->id_url_redirect = $redirectApi['redirect']['id'];
        $affiliate->save();
        return[
            'status'=>true,
            'message'=>'Your redirect was created',
            'path'=>$redirectApi['redirect']['path']
        ];
    }
    public function updateCustomAffLink($data)
    {
        $affiliate=auth('affiliate-api')->user();
        $shop=Shop::findOrFail($affiliate->shop_id);
        $result=Validator::make($data,
        [
            'path' =>[
                'required',
                function ($attribute, $value, $fail) use ($affiliate) {
                    $affiliatesCustomLinkExist = Affiliate::where([
                        'id' => $affiliate->id,
                        'affiliates_custom_link' =>'/'. $value
                    ])->get();
                    if ($affiliatesCustomLinkExist->count() != 0) {
                        $fail('Path has already been taken');

                    }
                }
            ]
            ]);
        $result->validate();
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $redirectApi=new Redirect($clientApi);
        $params=[
            "redirect"=> [
                "path"=>$data['path'],
              ]
            ];
        $redirectApi=$redirectApi->update($affiliate->id_url_redirect,$params);
        if(isset($redirectApi['errors']))
        {
            return [
                'status'=>false,
                'message'=>$redirectApi['errors']['path']['0'],
                'path'=>''
            ];
        }
        $affiliate->affiliates_custom_link = $redirectApi['redirect']['path'];
        $affiliate->id_url_redirect = $redirectApi['redirect']['id'];
        $affiliate->save();
        return[
            'status'=>true,
            'message'=>'Your redirect was created',
            'path'=>$redirectApi['redirect']['path']
        ];
    }
}