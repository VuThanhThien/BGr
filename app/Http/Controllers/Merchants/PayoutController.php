<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PayoutService;

class PayoutController extends Controller
{

    private PayoutService $payoutService;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function getPendingPayment(Request $request)
    {
        $data = $this->payoutService->getPendingPayment($request->all());
        return $this->successResponse($data, 'success', 200);
    }

    public function getPaidPayment(Request $request)
    {
        $payments = $this->payoutService->getPaidPayment($request->all());
        return $this->successResponse($payments, 'success', 200);
    }

    public function singlePayout(Request $request) 
    {
        return $this->payoutService->singlePayout($request->all());
    }   

    public function undoPayout(Request $request)
    {   $id = $request->get('id');
        return $this->payoutService->undoPayout($id);
    }

    public function getPaymentOrder(Request $request, $id) 
    {
        $orders = $this->payoutService->getPaymentOrder($request->all(),$id);
        return $this->successResponse($orders, 'success', 200);
    }

    public function storeCredit(Request $request)
    {
        return $this->payoutService->storeCredit($request->all());
    }

    public function addCredit(Request $request)
    {
        return $this->payoutService->addCredit($request->all());
    }

}
