<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopifyGdprHistory;

class ShopifyGdprWebhookController extends Controller
{
    public function dataRequest(Request $request)
    {
        $requestData = $request->all();
        $this->store('data_request', $requestData);
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function customerRedact(Request $request)
    {
        $requestData = $request->all();
        $this->store('customer_redact', $requestData);
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function shopRedact(Request $request)
    {
        $requestData = $request->all();
        $this->store('shop_redact', $requestData);
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function store($type, $requestData)
    {
        $shopifyGdprHistory = new ShopifyGdprHistory;
        $shopifyGdprHistory->type = $type;
        $shopifyGdprHistory->shop_id = $requestData['shop_id'];
        $shopifyGdprHistory->shop = $requestData['shop_domain'];
        $shopifyGdprHistory->data = $requestData;
        $shopifyGdprHistory->save();
        return true;
    }
}
