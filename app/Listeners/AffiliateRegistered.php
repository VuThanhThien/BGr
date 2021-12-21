<?php

namespace App\Listeners;

use App\Events\AffiliateRegisteredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AffiliateCoupon;
use App\Models\Group;
use App\Mail\AffiliateNotices\AffiliateReview;
use App\Models\Shop;
use App\Models\ShopSetting;
use App\Traits\NoticeAffiliate;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\PriceRule;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\DiscountCode;
use Illuminate\Support\Str;
use App\Services\AffiliateCouponService;

class AffiliateRegistered
{
    use NoticeAffiliate;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AffiliateRegisteredEvent  $event
     * @return void
     */
    public function handle(AffiliateRegisteredEvent $event)
    {
        $affiliate = $event->affiliate;
        $group = Group::where('id', $affiliate->group_id)->select('auto_approve_affiliate', 'mlm_bonus', 'is_enable_mlm')->first();

        if( !$group->auto_approve_affiliate ) {
            $this->sendNoticeToAffiliate($affiliate, 'affiliate_review');
        }else{
            $this->sendNoticeToAffiliate($affiliate, 'approved_affiliate');
        }
        if($this->checkCouponCodeAutomatic($affiliate))
        {
            $shop=Shop::where('id',$affiliate->shop_id)->first();
            $shopSettings = ShopSetting::where('shop_id',$affiliate->shop_id)->first();
            $priceRule =  $shopSettings->price_rule;
            $priceRuleId = $priceRule['price_rule'];
            if($priceRule['coupon_style']=='name')
            {
                $couponCode = couponCodeFromAffiliateName($affiliate->first_name . $affiliate->last_name) ;
            }
            else{
                $couponCode=strtoupper(Str::random(10));
            }
            $shopName = shopNameFromDomain($shop->shop);
            $checkAffiliateExist = AffiliateCoupon::where([
                'shop_id' =>  $shop->id,
                'code' =>$couponCode
            ])->get();           
            if(!count($checkAffiliateExist))
            {
                $couponService=new AffiliateCouponService;
                $couponService->createCouponCode($shopName,$shop,$couponCode,$priceRuleId,$affiliate );          
            }
            else{
                $couponCode = $couponCode.$this->checkExistAndGetRandomHashcode($couponCode, $shop->id);
                $couponService=new AffiliateCouponService;
                $couponService->createCouponCode($shopName,$shop,$couponCode,$priceRuleId,$affiliate);          
            }
        }

        //check and add mlm bonus
        if($affiliate->parent_id != 0 && $group->is_enable_mlm && $group->mlm_bonus > 0) {
            $this->addBonusMlm($affiliate, $group);
        }
        
    }
    private function checkCouponCodeAutomatic($affiliate)
    {
         $shopSettings=ShopSetting::where('shop_id',$affiliate->shop_id)->first();
         $isCouponCodeAutomatically= $shopSettings->auto_generate_coupon;
         $isExistPriceRule= $shopSettings->price_rule;
         if($isCouponCodeAutomatically && $isExistPriceRule){
            return true;
         }
         else{
             false;
         }
    }
     
    private function checkExistAndGetRandomHashcode($code, $shopId)
    {
        while(1) {
            $hashcode = rand(1,100);
            $check = AffiliateCoupon::where('code',$code.$hashcode)->where('shop_id',$shopId)->first();
            if( !$check ) {
                break;
            }
        }
        return $hashcode;
    }

    private function addBonusMlm($affiliate , $group)
    {
        $newConversion = new \App\Models\Conversion();
        $newConversion->commission = $group->mlm_bonus;
        $newConversion->total = 0;
        $newConversion->subtotal = 0;
        $newConversion->commission_type = 0;
        $newConversion->commission_amount = 0;
        $newConversion->quantity = 0;
        $newConversion->affiliate_id = $affiliate->parent_id;
        $newConversion->shop_id = $affiliate->shop_id;
        $newConversion->group_id = $affiliate->group_id;
        $newConversion->status = 2;
        $newConversion->tracking_type = \App\Models\Conversion::RECRUITMENT_BONUS;
        $newConversion->commission_explanation = ['id'=>$affiliate->id, 
        'email'=>$affiliate->email, 
        'name'=>$affiliate->first_name.' '.$affiliate->last_name];
        $newConversion->save();
    }

}
