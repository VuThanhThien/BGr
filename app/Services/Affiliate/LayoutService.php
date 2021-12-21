<?php

namespace App\Services\Affiliate;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\ShopInfo;
use App\Models\ShopPaymentMethod;
use App\Models\ShopSetting;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use App\Services\GroupService;
use Illuminate\Support\Facades\DB;

Class LayoutService
{

    private GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function getData()
    {
        
        $shopId = auth('affiliate-api')->user()->shop_id;
        $group=Affiliate::find(auth('affiliate-api')->user()->id)->group; 
        $shopSetting = ShopSetting::where('shop_id', $shopId)->with('info:shop_id,currency,money_format,name,domain,myshopify_domain')->first();
        $paymentMethodAvailable = null;
        if($group->payment_methods) {
            $paymentMethodAvailable = PaymentMethod::whereIn('id', $group->payment_methods)->get();
        }

        if(!$paymentMethodAvailable){
            $paymentMethodAvailable = ShopPaymentMethod::where('shop_payment_methods.shop_id', $shopId)
            ->join('payment_methods', 'payment_methods.id', '=', 'shop_payment_methods.method_id')
            ->select('payment_methods.*')
            ->get();
        }
        
        $translations = $this->getTranslations($shopSetting->default_affiliate_language);
        return [
            'shopSettings' => $shopSetting,
            'paymentMethodAvailable' => $paymentMethodAvailable,
            'group'=>$group?$group:['is_enable_mlm'=>0],
            'translations' => $translations
        ];
    }
    
    public function getTranslations($locale)
    {
        $shopId = auth('affiliate-api')->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id',$shopId)->select('default_affiliate_language')->first();
        $languageAffiliate =  $shopSetting->default_affiliate_language;
        if($locale)
        {
            $languageAffiliate = $locale;
        }
        $translations = DB::table('translates as t1')->where('t1.locale',$languageAffiliate)->
        where('t1.shop','default')->leftJoin('translates as t2',function($leftJoin) use( $shopId){
            $leftJoin->on('t1.locale','=','t2.locale');
            $leftJoin->on('t1.key','=','t2.key');
            $leftJoin->where('t2.shop',$shopId);
        })->select('t1.id','t1.key','t1.text','t2.text as t_text')->get();
        $translations = $this->convertArrayIndexToKey($translations);
        return [
            'locale' => $languageAffiliate,
            'translations' => $translations
        ];
    }

    protected function convertArrayIndexToKey($arr)
    {
        $temp = [];
        foreach($arr as $v)
        {
            $v->t_text? $temp[$v->key] = $v->t_text:  $temp[$v->key] = $v->text;   
        }
        return $temp;
    }

    public function getAffiliateAcountLanguage(){
        $shopId = auth('affiliate-api')->user()->shop_id;
        $shopSetting = ShopSetting::where('shop_id',$shopId)->select('default_affiliate_language','enable_affiliate_language','auto_detect_language')->first();
        return $shopSetting;
    }
}
