<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\AffiliateCoupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\PriceRule;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\DiscountCode;
use Illuminate\Support\Facades\Validator;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use Exception;

Class AffiliateCouponService
{

    public function getListCoupons($requestData)
    {
        $shopId = Auth::user()->shop_id;
        $coupons = AffiliateCoupon::where('shop_id', $shopId);

        if( isset($requestData['affiliate']) ) {
            $coupons = $coupons->where('affiliate_id', $requestData['affiliate']);
        }
        if( isset($requestData['search']) && !empty($requestData['search'])) {
            $query = addslashes($requestData["search"]);
            $coupons = $coupons->where('code', 'like', '%' . $query . '%');
        }
        $coupons = $coupons->with('affiliate:id,first_name,last_name,email')
        ->orderBy($requestData['sort_field'], $requestData['sort_direction'])
        ->paginate($requestData['paginate']);
        return $coupons;
    }

    public function assignCoupon($requestData)
    {
        $shopId = Auth::user()->shop_id;
        if(isset($requestData['discountAmount']) && $requestData['discountType']!=3)
        {
            $result=Validator::make($requestData,
            [
                'couponCode' =>[
                    'required',
                    function ($attribute, $value, $fail) use ($shopId) {
                        $checkAffiliateExist = AffiliateCoupon::where([
                            'shop_id' => $shopId,
                            'code' => $value
                        ])->get();
                        if ($checkAffiliateExist->count() != 0) {
                            $fail('This coupon has been assigned to another affiliate');
    
                        }
                    }
                ],
                'affiliate' => 'required',
                'discountAmount'=>['required', function ($attribute, $value, $fail) use($requestData)  {
                    if (intval($value)<0) {
                        $fail('The discount amount must be greater than or equal to 0');
                    }
                    else{
                        if($requestData['discountType']==1 && intval($value)>100)
                        {
                            $fail('The amount must be between 100 and 0');
                        }
                    }
                }]
            ]);
        }
        else{
            $result=Validator::make($requestData,
        [
            'couponCode' =>[
                'required',
                function ($attribute, $value, $fail) use ($shopId) {
                    $checkAffiliateExist = AffiliateCoupon::where([
                        'shop_id' => $shopId,
                        'code' => $value
                    ])->get();
                    if ($checkAffiliateExist->count() != 0) {
                        $fail('This coupon has been assigned to another affiliate');

                    }
                }
            ],
            'affiliate' => 'required',
        ]);  
        }
      
            $result->validate();
            $shop=Shop::findOrFail($shopId);
            $shopName = shopNameFromDomain($shop->shop);
            $clientApi = new ClientApi($shopName, '', $shop->access_token);
            $couponAPI=new DiscountCode($clientApi);
            if(!$requestData['isExist'])
            {
                // $startAt=Carbon::createFromTimestamp(intval($requestData['startAt']))->toDayDateTimeString();
                $params=["price_rule"=> [
                    "target_type"=>$requestData['discountType']==3? "shipping_line":"line_item",
                    "title"=> $requestData['couponCode'],
                    "target_selection"=>"all",
                    "allocation_method"=>$requestData['discountType']==3?"each":"across",
                    "value_type"=>$this->convertDiscountType($requestData['discountType']),
                    "value"=>$this->convertDiscountAmount($requestData['discountType'],$requestData['discountAmount']),
                    'starts_at'=>Carbon::now()->format('Y-m-d\TH:i:s'),
                    "customer_selection"=>"all"
                  ]];
     
                $priceRuleAPI=new PriceRule( $clientApi);
                $priceRuleAPI= $priceRuleAPI->create($params);
                $paramsDiscountCode=["discount_code"=> [
                    "code"=> $requestData['couponCode']
                  ]];
                if(isset($priceRuleAPI['errors']))
                {
                    return [
                        'status'=>false,
                        'message'=>'Error'
                    ];
                }
                $couponAPI=$couponAPI->create($priceRuleAPI['price_rule']['id'],$paramsDiscountCode );
                if(isset($couponAPI['errors']))
                {
                    return [
                        'status'=>false,
                        'message'=>'This coupon has already existed on your Shopify discount,
                         please select "Use an existing coupon"'
                    ];
                }
                $coupon = new AffiliateCoupon;
                $coupon->shop_id = $shopId;
                $coupon->affiliate_id = $requestData['affiliate'];
                $coupon->code = $couponAPI['discount_code']['code'];
                $discount_type=1;
                if($priceRuleAPI['price_rule']['value_type']=='percentage')
                {
                    $discount_type=1;
                }
                else{
                    if($priceRuleAPI['price_rule']['value_type']=='fixed_amount')
                    {
                        $discount_type=2;
                    }
                    else{
                        $discount_type=3;
                    }
                }
                $coupon->is_auto=1;
                $coupon->discount_type=  $discount_type;
                $coupon->discount_amount=$priceRuleAPI['price_rule']['value'];
                $coupon->save();
                $coupon->load('affiliate:id,first_name,last_name,email');
                return [
                    'status'=>true,
                    'message'=>'The coupon is created'
                ];    
            }
            else{
                $couponAPISearch = $couponAPI->search(['code'=> $requestData['couponCode']]); 
                if(isset($couponAPISearch['errors']))
                {
                    return [
                        'status'=>false,
                        'message'=>'This coupon does not exist in your Shopify store'
                    ];
                }
                $priceRuleAPI=new PriceRule( $clientApi);
                $priceRuleAPI= $priceRuleAPI->find($couponAPISearch['discount_code']['price_rule_id']);
                $coupon = new AffiliateCoupon;
                $coupon->shop_id = $shop->id;
                $coupon->affiliate_id = $requestData['affiliate'];
                $coupon->code = $couponAPISearch['discount_code']['code'];
                $discount_type=1;
                if($priceRuleAPI['price_rule']['value_type']=='percentage' && $priceRuleAPI['price_rule']['target_type']=='line_item')
                {
                    $discount_type=1;
                }
                else{
                    if($priceRuleAPI['price_rule']['value_type']=='fixed_amount')
                    {
                        $discount_type=2;
                    }
                    else{
                        $discount_type=3;
                    }
                }
                $coupon->is_auto=1;
                $coupon->discount_type= $discount_type;
                $coupon->discount_amount=$priceRuleAPI['price_rule']['value'];
                $coupon->save();
                return [
                    'status'=>true,
                    'message'=>'The coupon is created'
                ];
            }
        
    }
    protected function convertDiscountType($type)
    {
        $valueType='';
        if($type==1)
        {
            $valueType='percentage';
        }
        else{
            if($type==2)
            {
               $valueType ='fixed_amount';
            }
            else
            {
                $valueType='percentage';
            }
        }
        return $valueType;
    }
    
    protected function convertDiscountAmount($type,$value)
    {
        if($type==1)
        {
            $value=$value*-1.00;
        }
        if($type==2)
        {
            $value=$value*-1.00;
        }
       if($type==3){
            $value=-100.0;
        }
        return $value;
    }

    public function delete($removeInShopify,$id) 
    {
        $affiliateCoupon=AffiliateCoupon::find($id);
        if(!$removeInShopify)
        {
            if(!auth()->user()->can('delete',$affiliateCoupon))
            {
                throw new \Illuminate\Auth\Access\AuthorizationException();
            }
            $affiliateCoupon->delete();
            return true;
        }
        $shopId = Auth::user()->shop_id;
        $couponCode = $affiliateCoupon->code;
        $shop=Shop::findOrFail($shopId);
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $couponAPI=new DiscountCode($clientApi);
        $couponAPISearch = $couponAPI->search(['code'=> $couponCode]);
        if(isset($couponAPISearch['errors']))
        {
            return false;
        }
        $couponAPI->delete($couponAPISearch['discount_code']['price_rule_id'], $couponAPISearch['discount_code']['id']);
        if(!auth()->user()->can('delete',$affiliateCoupon))
            {
                throw new \Illuminate\Auth\Access\AuthorizationException();
            }
        $affiliateCoupon->delete();
        return true;
    }
    public function createCouponCode($shopName,$shop,$couponCode,$priceRuleId,$affiliate)
    {
        try{
            $clientApi = new ClientApi($shopName, '', $shop->access_token);
            $couponAPIShop=new DiscountCode($clientApi);
            $params=["discount_code"=> [
            "code"=> $couponCode
          ]];
          $priceRuleAPI=new PriceRule( $clientApi);
          $priceRuleAPI= $priceRuleAPI->find($priceRuleId);
          if(isset($priceRuleAPI['errors']))
          {
              return '';
          }
          while(true)
          {
            $couponAPI=$couponAPIShop->create($priceRuleId,$params);
            if(!isset($couponAPI['errors']))
            {
                break;
            }
            $params=["discount_code"=> [
                "code"=> $couponCode.$this->checkCouponExistAndGetRandomHashcode()
              ]];
          }
             $coupon = new AffiliateCoupon;
             $coupon->shop_id = $shop->id;
             $coupon->affiliate_id = $affiliate->id;
             $coupon->code = $couponAPI['discount_code']['code'];
             $discount_type=1;
             if($priceRuleAPI['price_rule']['value_type']=='percentage')
             {
                 $discount_type=1;
             }
             else{
                 if($priceRuleAPI['price_rule']['value_type']=='fixed_amount')
                 {
                     $discount_type=2;
                 }
                 else{
                     $discount_type=3;
                 }
             }
             $coupon->is_auto=1;
             $coupon->discount_type=  $discount_type;
             $coupon->discount_amount=$priceRuleAPI['price_rule']['value'];
             $coupon->save();
             return $coupon->code;
        }
        catch(\Exception $ex)
        {
            info($shop->id.':Error Api create coupon');
            return '';
        }

    }
 
    private function checkCouponExistAndGetRandomHashcode()
    {
        $hashcode = rand(1,100);
        return $hashcode;
    }
    
}
