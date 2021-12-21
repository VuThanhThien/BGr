<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Affiliate\PayoutService;
class PayoutController extends Controller
{
    private PayoutService $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }
    public function getPaidPayment(Request $request)
    {
        $payments = $this->payoutService->getPaidPayment($request->all());
        return $this->successResponse($payments, 'success', 200);
    }
    public function getPaymentOrder(Request $request,$id)
    {
        $orders = $this->payoutService->getPaymentOrder($request->all(),$id);
        return $this->successResponse($orders, 'success', 200);
    }

    public function getStoreCreditCoupon(Request $request)
    {
        $coupons = $this->payoutService->getStoreCreditCoupon($request->all());
        return $this->successResponse($coupons, 'success', 200);
    }

    public function getStoreCreditWalet()
    {
        $walets =  $this->payoutService->getStoreCreditWalet();
        return $this->successResponse($walets, 'success', 200);
    }

    public function storeDiscountCode(Request $request)
    {
         return $this->payoutService->storeDiscountCode($request->all());
    }

}
