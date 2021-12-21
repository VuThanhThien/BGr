<?php

namespace App\Services\Affiliate;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\ShopInfo;
use App\Models\Conversion;
use App\Models\Payment;
use App\Models\ShopSetting;
use App\Models\StoreCreditCoupon;
use App\Models\StoreCreditWalet;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\DiscountCode;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\PriceRule;

Class PayoutService
{
    use ApiResponser;

    public function getPaidPayment($requestData)
    {
        $affiliate= auth('affiliate-api')->user();
        $payments = Payment::where('affiliate_id', $affiliate->id)->withCount('conversion');
        if(isset($requestData['affiliate'])) {
            $payments = $payments->where('payments.affiliate_id', $requestData['affiliate']);
        }

        if(isset($requestData['start_date'])) {
            $startDate = Carbon::createFromTimestamp( intval($requestData['start_date']) )->toDateTimeString();
            $endDate = Carbon::createFromTimestamp( intval($requestData['end_date']) )->toDateTimeString();
            $payments = $payments->whereBetween('payments.created_at', [$startDate, $endDate]);
        }

        $payments = $payments->with(['affiliate:id,first_name,last_name,email'])->withCount('conversion as total_orders')->paginate( $requestData['paginate'] );
        return $payments;
    }
    public function getPaymentOrder($requestData,$id)
    {
        $affiliate=auth('affiliate-api')->user();
        $orders=Conversion::where([
            ['affiliate_id','=',$affiliate->id],
            ['payment_id','=',$id]   
        ]
     
        )->select('id','order_name', 'commission', 'created_at', 'total', 'affiliate_id')->paginate($requestData['paginate']);
        return $orders;
    }

    public function getStoreCreditCoupon($data)
    {
        $affiliate = auth('affiliate-api')->user();
        $storeCreditCoupons = StoreCreditCoupon::where('affiliate_id',$affiliate->id);
        if(!empty($data['search']))
        {
            $query = addslashes($data['search']);
            $storeCreditCoupons = $storeCreditCoupons->where('code','like','%'.$query.'%');
        }
        $storeCreditCoupons =  $storeCreditCoupons->orderBy($data['sort_field'],$data['sort_direction'])->paginate($data['paginate']);
        return $storeCreditCoupons ;
    }

    public function getStoreCreditWalet()
    {
        $affiliate = auth('affiliate-api')->user();
        $storeCreditWalets = StoreCreditWalet::where('affiliate_id',$affiliate->id)->first();
        return  $storeCreditWalets? floatval($storeCreditWalets->total) : 0;
    }

    public function storeDiscountCode($data)
    {
        $affiliate = auth('affiliate-api')->user();
        $shop = Shop::findOrFail($affiliate->shop_id);
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $priceRuleAPI = new PriceRule($clientApi);
        $couponAPI = new DiscountCode($clientApi);
        $shopSettings = ShopSetting::where('shop_id',$affiliate->shop_id)->select('autopay_discount_code')->first();
        $conversion_factor = $shopSettings->autopay_discount_code? floatval($shopSettings->autopay_discount_code['conversion_factor']): 1;
        $prefix = $shopSettings->autopay_discount_code['prefix']?$shopSettings->autopay_discount_code['prefix']:'';
        $couponCode = $this->checkCouponExistAndGetRandomCoupon($affiliate->shop_id,$prefix);
        $paramsDiscountCode = ["discount_code" => [
            "code" => $couponCode
        ]];
        $params = ["price_rule" => [
            "target_type" => "line_item",
            "title" => $couponCode,
            "target_selection" => "all",
            "allocation_method" => "across",
            "value_type" => "fixed_amount",
            "value" => $data['amount'] * $conversion_factor * -1.00,
            "customer_selection" => "all",
            "usage_limit" => 1,
            'starts_at' => Carbon::now()->format('Y-m-d\TH:i:s')
        ]];
        $priceRuleAPI = $priceRuleAPI->create($params);
        if (!isset($priceRuleAPI['errors'])) {
            while (true) {
                $couponAPI = $couponAPI->create($priceRuleAPI['price_rule']['id'], $paramsDiscountCode);
                if (!isset($couponAPI['errors'])) {
                    break;
                }
                $paramsDiscountCode = ["discount_code" => [
                    "code" => $this->checkCouponExistAndGetRandomCoupon($affiliate->shop_id,$prefix)
                ]];
            }
            $storeCreditCoupon = new StoreCreditCoupon;
            $storeCreditCoupon->code = $couponAPI['discount_code']['code'];
            $storeCreditCoupon->affiliate_id =  $affiliate->id;
            $storeCreditCoupon->shop_id = $affiliate->shop_id;
            $storeCreditCoupon->discount_type = 2;
            $storeCreditCoupon->discount_amount = $data['amount'] * $conversion_factor;
            $storeCreditCoupon->save();
            
            $storeCreditWalets = StoreCreditWalet::where('affiliate_id',$affiliate->id)->first();
            $storeCreditWalets->total -= $data['amount'];
            $storeCreditWalets->save();
            return $this->successResponse([
                'coupon_code'=> $couponAPI['discount_code']['code'],
                'remaining_balance' => $storeCreditWalets->total,
                'discount_amount' => $data['amount'] * $conversion_factor
            ],'success',200);
        } else {
            return $this->errorResponse('Error', 500);
        }
    }

    public function checkCouponExistAndGetRandomCoupon($shopId,$prefix)
    {
        while(true)
        {
            $hashcode = $prefix? $prefix.'_'.strtoupper(generateRandomString(8)):strtoupper(generateRandomString(8));
            $exist = StoreCreditCoupon::where('shop_id',$shopId)->where('code',$hashcode)->first();
            if(!$exist)
            {
                break;
            }
        }
        return $hashcode;
    }
}
