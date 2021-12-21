<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\ShopInfo;
use App\Models\Conversion;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use App\Events\CommissionPayoutEvent;
use App\Models\ShopSetting;
use App\Models\StoreCreditCoupon;
use App\Models\StoreCreditWalet;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\DiscountCode;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts\PriceRule;
use Illuminate\Support\Facades\DB as FacadesDB;

class PayoutService
{
    use ApiResponser;

    public function getPendingPayment($data)
    {

        $shopId = Auth::user()->shop_id;
        $conditions = [['conversions.status', '=', 1], ['conversions.shop_id', '=', $shopId]];

        if (isset($input['group'])) {
            if ($input['group'] >= 0) {
                $conditions[] = ['conversions.group_id', '=', $input['group']];
            }
        }

        $payments = Conversion::where($conditions);

        if (isset($data['start_date'])) {
            $startDate = Carbon::createFromTimestamp(intval($data['start_date']))->toDateTimeString();
            $endDate = Carbon::createFromTimestamp(intval($data['end_date']))->toDateTimeString();
            $payments = $payments->whereBetween('conversions.created_at', [$startDate, $endDate]);
        }

        if (isset($data['affiliate'])) {
            $payments = $payments->where('conversions.affiliate_id', $data['affiliate']);
        }

        if (isset($data['group'])) {
            $payments = $payments->where('conversions.group_id', $data['group']);
        }
        if (isset($data['min_payout'])) {
            $payments = $payments->having('amount', '>=', $data['min_payout']);
        }
        $payments = $payments->has('affiliate');
        $payments = $payments->select('conversions.affiliate_id', DB::raw('count(*) as total_orders'), DB::raw('sum(commission) as amount'))
            ->with('affiliate.setting')
            ->groupBy('conversions.affiliate_id')
            ->orderBy($data['sort_field'], $data['sort_direction'])
            ->paginate($data['paginate']);
        return $payments;
    }

    public function getPaidPayment($requestData)
    {
        $shopId = Auth::user()->shop_id;
        $payments = Payment::where('shop_id', $shopId);
        if (isset($requestData['affiliate'])) {
            $payments = $payments->where('payments.affiliate_id', $requestData['affiliate']);
        }

        if (isset($requestData['start_date'])) {
            $startDate = Carbon::createFromTimestamp(intval($requestData['start_date']))->toDateTimeString();
            $endDate = Carbon::createFromTimestamp(intval($requestData['end_date']))->toDateTimeString();
            $payments = $payments->whereBetween('payments.created_at', [$startDate, $endDate]);
        }

        $payments = $payments->with(['affiliate:id,first_name,last_name,email'])->withCount('conversion as total_orders')->paginate( $requestData['paginate'] );
        return $payments;
    }

    public function singlePayout($data)
    {
        $shopId = Auth::user()->shop_id;
        $newTotalOrders = Conversion::where('affiliate_id', $data['affiliate_id'])->where('status', 1)->groupBy('affiliate_id')->count();

        //so khớp số lượng order hiện tại với số lượng order ở thời điểm bấm create payment
        if ($newTotalOrders != $data['total_orders']) {
            return $this->errorResponse('This affiliate has just referred a new order, please refresh this page and try again', 400);
        }
        $arr = DB::transaction(function () use ($shopId, $data) {

            $newPayment = new Payment;
            $newPayment->shop_id = $shopId;
            $newPayment->amount = $data['amount'];
            $newPayment->affiliate_id = $data['affiliate_id'];
            $newPayment->payment_note = $data['payment_note'];
            $newPayment->affiliate_note = $data['affiliate_note'];
            $newPayment->payment_method = $data['payment_method'];
            $newPayment->payment_info = $data['payment_info'];
            $newPayment->save();
            $paymentId = $newPayment->id;

            $query = Conversion::where('affiliate_id', $data['affiliate_id'])
                ->where('shop_id', $shopId)
                ->where('status', 1);
            // if( isset($input['referral_ids']) ) {
            //     $query = $query->whereIn('id', $input['referral_ids']);
            // }
            // if( isset($input['from_date']) ) {
            //     $from = Carbon::createFromTimestamp( intval($input['from_date']) )->toDateTimeString();
            //     $to = Carbon::createFromTimestamp( intval($input['to_date']) )->toDateTimeString();
            //     $query = $query->whereBetween('created_at', [$from, $to]);
            // }
            $query->update(['status' => 3, 'payment_id' => $paymentId,  'updated_at' => Carbon::now()]);
            return compact('newPayment');
        }, 5);

        if (isset($arr['newPayment'])) {
            $affiliate = Affiliate::where('id', $data['affiliate_id'])->select('id', 'first_name', 'last_name', 'email')->first();
            event(new CommissionPayoutEvent($affiliate, $arr['newPayment']));
        }

        return $this->successResponse($arr, 'success', 200);
    }

    public function undoPayout($id)
    {
        $shopId = Auth::user()->shop_id;
        $result = DB::transaction(function () use ($id, $shopId) {
            $payment = Payment::where('id', $id)->where('shop_id', $shopId)->first();
            if($payment->is_add_credit)
            {
              $storeCreditWallet = StoreCreditWalet::where('affiliate_id',$payment->affiliate_id)->where('shop_id',$shopId)->first();
              if(floatval($storeCreditWallet->total)  < floatval($payment->amount))
              {
                  return false;
              }
              else
              {
                $storeCreditWallet->total -= $payment->amount;
                $storeCreditWallet->save();
              }
            }
            $payment->delete();
            Conversion::where('payment_id', $id)->where('shop_id', $shopId)->update(['status' => 1, 'payment_id' => null]);
            return true;
        }, 5);
        if($result)
        {
            return $this->successResponse('', 'success', 200);
        }
        else
        {
            return $this->errorResponse('Cannot undo the payment because this affiliate\'s store credit balance is not enough', 400);
        }

    }

    public function getPaymentOrder($requestData, $id)
    {
        $orders = Conversion::where('payment_id', $id)
            ->select('id', 'order_name', 'commission', 'created_at', 'total', 'affiliate_id', 'level')
            ->with('affiliate:id,first_name,last_name')
            ->paginate($requestData['paginate']);
        return $orders;
    }

    public function storeCredit($data)
    {
        $shopId = auth()->user()->shop_id;
        $newTotalOrders = Conversion::where('affiliate_id', $data['affiliate_id'])->where('status', 1)->groupBy('affiliate_id')->count();
        if ($newTotalOrders != $data['total_orders']) {
            return $this->errorResponse('This affiliate has just referred a new order, please refresh this page and try again', 400);
        }
        $shopSettings = ShopSetting::where('shop_id', $shopId)->select('shop_id')->with('info:shop_id,money_format')
            ->first();
        $arr = DB::transaction(function () use ($shopId, $data, $shopSettings) {
            $shop = Shop::findOrFail($shopId);
            $shopName = shopNameFromDomain($shop->shop);
            $clientApi = new ClientApi($shopName, '', $shop->access_token);
            $priceRuleAPI = new PriceRule($clientApi);
            $couponAPI = new DiscountCode($clientApi);
            $couponCode = $this->checkCouponExistAndGetRandomCoupon($shopId);
            $paramsDiscountCode = ["discount_code" => [
                "code" => $couponCode
            ]];
            $params = ["price_rule" => [
                "target_type" => "line_item",
                "title" => $couponCode,
                "target_selection" => "all",
                "allocation_method" => "across",
                "value_type" => "fixed_amount",
                "value" => $data['amount'] * -1.00,
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
                        "code" => $this->checkCouponExistAndGetRandomCoupon($shopId)
                    ]];
                }
                $newPayment = new Payment;
                $newPayment->affiliate_id = $data['affiliate_id'];
                $newPayment->shop_id = $shopId;
                $newPayment->amount = $data['amount'];
                $newPayment->payment_note = 'Store discount coupon - ' . $couponAPI['discount_code']['code'];
                $newPayment->affiliate_note = 'Apply the store discount coupon at checkout to get ' . formatCommissionAmount(2, $data['amount'], $shopSettings->info->money_format) . ' off: ' . $couponAPI['discount_code']['code'];
                $newPayment->payment_method = 'discount_coupon';
                $newPayment->payment_info = null;
                $newPayment->save();
                $paymentId = $newPayment->id;

                $storeCreditCoupon = new StoreCreditCoupon;
                $storeCreditCoupon->code = $couponAPI['discount_code']['code'];
                $storeCreditCoupon->affiliate_id = $data['affiliate_id'];
                $storeCreditCoupon->shop_id = $shopId;
                $storeCreditCoupon->discount_type = 2;
                $storeCreditCoupon->discount_amount = $data['amount'];
                $storeCreditCoupon->save();
                
                Conversion::where('shop_id',$shopId)->where('affiliate_id',$data['affiliate_id'])->where('status',1)->update([
                    'payment_id' =>  $paymentId,
                    'status' => 3,
                    'updated_at' => Carbon::now()
                ]);
                return compact('newPayment');

            } else {
                return $this->errorResponse('Error', 500);
            }
        },5);
        if (isset($arr['newPayment'])) {
            $affiliate = Affiliate::where('id', $data['affiliate_id'])->select('id', 'first_name', 'last_name', 'email')->first();
            event(new CommissionPayoutEvent($affiliate, $arr['newPayment']));
        }
        return $this->successResponse(true, 'success', 200);
    }

    public function checkCouponExistAndGetRandomCoupon($shopId)
    {
        while(true)
        {
            $hashcode = strtoupper(generateRandomString(8));
            $exist = StoreCreditCoupon::where('shop_id',$shopId)->where('code',$hashcode)->first();
            if(!$exist)
            {
                break;
            }
        }
        return $hashcode;
    }

    public function addCredit($data)
    {
        $shopId = auth()->user()->shop_id;
        $newTotalOrders = Conversion::where('affiliate_id', $data['affiliate_id'])->where('status', 1)->groupBy('affiliate_id')->count();
        if ($newTotalOrders != $data['total_orders']) {
            return $this->errorResponse('This affiliate has just referred a new order, please refresh this page and try again', 400);
        }
        $shopSettings = ShopSetting::where('shop_id',$shopId)->select('shop_id')->with('info:shop_id,money_format')->first();
        $arr = DB::transaction(function() use($shopId, $shopSettings, $data)
        {

            $storeCreditWallet = StoreCreditWalet::firstOrCreate(
                ['affiliate_id' => $data['affiliate_id']],
                ['shop_id' => $shopId]
            );
            $storeCreditWallet->total += $data['amount'] ;
            $storeCreditWallet->save();
            $newPayment = new Payment;
            $newPayment->affiliate_id = $data['affiliate_id'];
            $newPayment->shop_id = $shopId;
            $newPayment->amount = $data['amount'];
            $newPayment->payment_note = 'This affiliate\'s store credit wallet was credited with '. formatCommissionAmount(2, $data['amount'], $shopSettings->info->money_format);
            $newPayment->affiliate_note = 'Your store credit wallet has been credited with '. formatCommissionAmount(2, $data['amount'], $shopSettings->info->money_format);
            $newPayment->payment_method = 'discount_coupon';
            $newPayment->payment_info = null;
            $newPayment->is_add_credit = 1;
            $newPayment->save();
            $paymentId = $newPayment->id;
            Conversion::where('shop_id',$shopId)->where('affiliate_id',$data['affiliate_id'])->where('status',1)->update([
                'payment_id' =>  $paymentId,
                'status' => 3,
                'updated_at' => Carbon::now()
            ]);
            return compact('newPayment');
        },5);
        if (isset($arr['newPayment'])) {
            $affiliate = Affiliate::where('id', $data['affiliate_id'])->select('id', 'first_name', 'last_name', 'email')->first();
            event(new CommissionPayoutEvent($affiliate, $arr['newPayment']));
        }
        return $this->successResponse(true, 'success', 200);
    }
}
