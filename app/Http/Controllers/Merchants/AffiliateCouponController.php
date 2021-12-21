<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AffiliateCouponService;

class AffiliateCouponController extends Controller
{
    private AffiliateCouponService $affiliateCouponService;

    public function __construct(AffiliateCouponService $affiliateCouponService)
    {
        $this->affiliateCouponService = $affiliateCouponService;
    }

    public function getListCoupons(Request $request)
    {
        $requestData = $request->all();
        $coupons = $this->affiliateCouponService->getListCoupons($requestData);
        return $this->successResponse($coupons, 'success', 200);
    }

    public function assignCoupon(Request $request)
    {
        $requestData = $request->only('affiliate', 'couponCode','discountType','discountAmount','startAt','isExist');
        $couponMessage = $this->affiliateCouponService->assignCoupon($requestData);
        return $this->successResponse($couponMessage, 'success', 200);
    }

    public function deleteCoupon($removeInShopify,$id)
    {
        $result=$this->affiliateCouponService->delete($removeInShopify,$id);
        return $this->successResponse($result, 'success', 200);    
    }
}
