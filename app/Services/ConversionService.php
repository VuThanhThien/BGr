<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\Conversion;
use App\Models\ShopSetting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\ApprovedConversionEvent;
use App\Events\DeniedConversionEvent;
use App\Models\Click;
use App\Traits\ApiResponser;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Orders\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Events\NewConversionEvent;

class ConversionService
{
    use ApiResponser;
    public function getListWithPaginate($data)
    {
        $shopId = auth()->user()->shop_id;
        $conditions = [['conversions.shop_id', '=', $shopId]];
        if (isset($data['affiliate'])) {
            $conditions[] = ['affiliate_id', $data['affiliate']];
        }
        if (isset($data['status'])) {
            $conditions[] = ['status', $data['status']];
        }

        if (isset($data['level'])) {
            $conditions[] = ['level', $data['level']];
        }

        $conversions = Conversion::where($conditions);

        if (isset($data['start_date'])) {
            $startDate = Carbon::createFromTimestamp(intval($data['start_date']))->toDateTimeString();
            $endDate = Carbon::createFromTimestamp(intval($data['end_date']))->toDateTimeString();
            $conversions = $conversions->whereBetween('conversions.created_at', [$startDate, $endDate]);
        }
        $conversions = $conversions->with(['network' => function ($query) {
            $query->select('conversion_id', DB::raw('sum(commission) as total_commission'))->groupBy('conversion_id');
        }]);
        $conversions = $conversions->with('affiliate:id,first_name,last_name')->orderBy($data['sort_field'], $data['sort_direction'])->paginate($data['paginate']);
        return $conversions;
    }

    public function checkFirstApprove()
    {
        $shopSetting = ShopSetting::where('shop_id', auth()->user()->shop_id)->select('id', 'first_click_approve_conversion')->first();
        if (!$shopSetting->first_click_approve_conversion && !$shopSetting->is_review) {
            $shopSetting->first_click_approve_conversion = 1;
            $shopSetting->save();
            return true;
        } else {
            return false;
        }
    }

    public function updateStatus($id, $status)
    {
        $conversion = Conversion::find($id);
        if (!auth()->user()->can('update', $conversion)) {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        if ($conversion->status != 3) {
            $conversion->status = $status;
        }
        $conversion->save();
        if ($status == 1) {
            event(new ApprovedConversionEvent($conversion));
            $conversion->showPopupFeedback = $this->checkFirstApprove();
        }
        if ($status == 4) {
            event(new DeniedConversionEvent($conversion));
        }

        return $conversion;
    }


    public function getStatistics($data)
    {
        $shopId = auth()->user()->shop_id;
        $shop = Shop::find($shopId);
        $clicks = 0;
        $startDate = Carbon::createFromTimestamp(intval($data['start_date']))->toDateTimeString();
        $endDate = Carbon::createFromTimestamp(intval($data['end_date']))->toDateTimeString();
        $clicks = Click::whereHas('affiliate', function ($query) use ($shopId) {
            $query->whereHas('shop', function ($query) use ($shopId) {
                $query->where('id', $shopId);
            });
        })->whereBetween('date', [$startDate, $endDate])->sum('count');
        $res = Conversion::where('shop_id', $shopId)->where('level', 0)->whereBetween('created_at', [$startDate, $endDate])
            ->select([DB::raw('count(*) as orders'), DB::raw('sum(total) as revenue'), DB::raw('sum(commission) as commission')])
            ->groupBy('shop_id')->first();
        return [
            'clicks' => $clicks,
            'commission' => $res ? $res->commission : 0,
            'orders' => $res ? $res->orders : 0,
            'revenue' => $res ? $res->revenue : 0
        ];
    }

    public function getCommissionStatistics()
    {
        $shopId = auth()->user()->shop_id;
        $statistics = Conversion::where('shop_id', $shopId)
            ->select(
                DB::raw('sum(case when status=2 then commission else 0 end) as commission_pending'),
                DB::raw('sum(case when status=1 then commission else 0 end) as commission_approved'),
                DB::Raw('sum(case when status=3 then commission else 0 end) as commission_paid'),
                DB::Raw('sum(case when status=4 then commission else 0 end) as commission_rejected')
            )->first();
        if (!isset($statistics['commission_pending'])) {
            $statistics = [
                'commission_pending' => '0.00',
                'commission_approved' => '0.00',
                'commission_paid' => '0.00',
                'commission_rejected' => '0.00',
                'timestamp' => ''
            ];
        }
        return $statistics;
    }
    public function approveConversions($data, $status)
    {
        $listConversion = [];
        foreach ($data['conversionsId'] as $valueId) {
            $conversion = $this->updateStatus($valueId, $status);
            array_push($listConversion,  $conversion);
        }
        return $listConversion;
    }
    public function denyConversions($data, $status)
    {
        $listConversion = [];
        foreach ($data['conversionsId'] as $valueId) {
            $conversion = $this->updateStatus($valueId, $status);
            array_push($listConversion,  $conversion);
        }
        return $listConversion;
    }
    public function networkExplanation($id)
    {
        $conversion = Conversion::find($id);
        $networkExplanation = Conversion::where('order_id', $conversion->order_id)->where('level', '>', 0)
            ->join('affiliates', 'affiliates.id', '=', 'conversions.affiliate_id')
            ->select('conversions.level', 'conversions.commission', 'affiliates.first_name', 'affiliates.last_name')
            ->orderBy('conversions.level')->get();
        return $networkExplanation;
    }

    public function store($data)
    {
        $arrValidator = [
            'affiliate_id' => 'required'
        ];
        if ($data['type'] == 0) {
            $arrValidator['order_id'] = 'required';
        }
        if ($data['type'] == 1) {
            $arrValidator['commission'] = 'required|numeric|min:0';
        }
        Validator::make(
            $data,
            $arrValidator
        )->validate();
        $shopId = auth()->user()->shop_id;
        $affiliate = Affiliate::where('shop_id', $shopId)->where('id', $data['affiliate_id'])->first();
        $group = Group::where('shop_id', $shopId)->where('id', $affiliate->group_id)->first();
        if (!intval($data['type'])) {
            if(strpos($data['order_id'],'#')===false)
            {
                $data['order_id'] = '#'.$data['order_id'];
            }
            $checkConversion = Conversion::where('order_name', $data['order_id'])->where('shop_id', $shopId)->first();
            if (!$checkConversion || isset($data['is_replace'])) {
                $shop = Shop::findOrFail($shopId);
                $shopName = shopNameFromDomain($shop->shop);
                try {
                    $clientApi = new ClientApi($shopName, '', $shop->access_token);
                    $orderApi = new Order($clientApi);
                    $params = [
                        'name' => $data['order_id'],
                        'status' => 'any'
                    ];
                    $orderApi = $orderApi->all($params);
                    if (!isset($orderApi['orders'])) {
                        $result = [
                            'status' => 'error',
                            'message' => 'Order not found'
                        ];
                        return $this->successResponse($result, 'success', 200);
                    }
                    if (isset($orderApi['orders'])) {

                        $lengthOrder = count($orderApi['orders']);
                        if($lengthOrder == 1)
                        {
                            $dataOrder = $orderApi['orders'][0];
                            if (isset($data['is_replace'])) {
                                Conversion::where('shop_id', $shopId)->where('order_id', $dataOrder['id'])->delete();
                            }
                            $webhookOrderShopifyService = new WebhookOrderShopifyService;
                            $trackingType = Conversion::ADD_MANUAL;
                            if ($affiliate && $group->is_active) {
                                $commissionExplanation = $webhookOrderShopifyService->calCommission($dataOrder, $group);
                                $newConversion = $webhookOrderShopifyService->storeConversion($affiliate, $group, $dataOrder, $commissionExplanation, $trackingType);
                                $webhookOrderShopifyService->storeConversionNetwork($affiliate, $newConversion);
                                event(new NewConversionEvent($affiliate, $newConversion));
                            }
                        }
                        else
                        {
                            $result = [
                                'status' => 'error',
                                'message' => 'Order ' . $data['order_id'] . ' can not be found on your shop or was placed before the last 60 days' 
                            ];
                            return $this->successResponse($result, 'success', 200);                          
                        }
                    }
                    $result = [
                        'status' => 'ok',
                        'message' => 'Created'
                    ];
                    return $this->successResponse($result, 'success', 200);
                } catch (\Exception $ex) {
                    return $this->errorResponse($ex->getMessage(), 500);
                }
            } else {
                if ($checkConversion->affiliate_id != $data['affiliate_id']) {
                    $result = [
                        'status' => 'other_affiliate_exists',
                        'message' => 'This conversion has been added to another affiliate'
                    ];
                    return $this->successResponse($result, 'success', 200);
                } else {
                    $result =  [
                        'status' => 'this_affiliate_exists',
                        'message' => 'This conversion has already been added to this affiliate'
                    ];
                    return $this->successResponse($result, 'success', 200);
                }
            }
        } else {
            $conversion = new Conversion;
            $conversion->shop_id = $shopId;
            $conversion->affiliate_id = $data['affiliate_id'];
            $conversion->group_id = $affiliate->group_id;
            $conversion->status = $group->auto_approve_order ? 1 : 2;
            $conversion->tracking_type = Conversion::ADD_MANUAL;
            $conversion->total = isset($data['total']) ? $data['total'] : 0;
            $conversion->commission = $data['commission'];
            $conversion->comment = $data['comment'];
            $conversion->commission_type = $group->commission_type;
            $conversion->commission_amount = $group->commission_amount;
            $conversion->quantity = 0;
            $conversion->subtotal = 0;
            $conversion->save();
            if ($conversion->status == 1) {
                event(new \App\Events\CheckAutoTierEvent($affiliate));
            }
        }
        $result = [
            'status' => 'ok',
            'message' => 'Created'
        ];
        return $this->successResponse($result, 'success', 200);
    }

    public function saveComment($data, $id)
    {
        $shopId = auth()->user()->shop_id;
        Conversion::where('shop_id', $shopId)->where('id', $id)->update([
            'comment' => $data['comment']
        ]);
        return true;
    }
}
