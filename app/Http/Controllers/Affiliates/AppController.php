<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Affiliate;
use App\Models\ShopSetting;
use App\Models\Shop;
use App\Models\Group;
use App\Traits\ApiResponser;
use App\Models\ShopPaymentMethod;

class AppController extends Controller
{
    use ApiResponser;

    public function init(Request $request)
    {
        $responseData = [];
        $user = auth('affiliate-api')->user();
        $shopId = $user->shop_id;
        $affiliateSetting = $user->setting()->first();
        $paymentMethodAvailable = ShopPaymentMethod::where('shop_payment_methods.shop_id', $shopId)
        ->join('payment_methods', 'payment_methods.id', '=', 'shop_payment_methods.method_id')
        ->select('payment_methods.*')
        ->get();
        $responseData['affiliateSetting'] = $affiliateSetting;
        $responseData['paymentMethodAvailable'] = $paymentMethodAvailable;
        return $this->successResponse($responseData, 'success', 200);
    }

    public function loginAs(Request $request)
    {
        $token = $request->get('token');
        return view('affiliate', ['token' => $token]);
    }
}
