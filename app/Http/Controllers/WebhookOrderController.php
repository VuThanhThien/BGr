<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebhookOrderShopifyService;

class WebhookOrderController extends Controller
{
    private $handleOrder;

    public function __construct(WebhookOrderShopifyService $webhookOrderShopifyService)
    {
        $this->handleOrder = $webhookOrderShopifyService;
    }

    public function orderCreated(Request $request)
    {
        $data = $request->all();
        $this->handleOrder->handleHook($data);
        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ], 200);
    }

    public function orderPaid(Request $request)
    {
        $data = $request->all();
        $this->handleOrder->handleHook($data);
        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ], 200);
    }

    public function orderRefund(Request $request)
    {
        $data = $request->all();
    }

}
