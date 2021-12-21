<?php

namespace App\Services\Affiliate;

use App\Models\Affiliate;
use App\Models\AffiliateSetting;
use App\Models\Group;
use App\Models\Shop;
use App\Models\Conversion;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopPaymentMethod;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;

Class SettingService
{
    use ApiResponser;

    public function getSetting()
    {
        $responseData = [];
        $user = auth('affiliate-api')->user();
        $shopId = $user->shop_id;
        $setting = $user->setting()->first();
        $paymentMethodAvailable = ShopPaymentMethod::where('shop_payment_methods.shop_id', $shopId)
        ->join('payment_methods', 'payment_methods.id', '=', 'shop_payment_methods.method_id')
        ->select('payment_methods.*')
        ->get();
        $responseData['setting'] = $setting;
        $responseData['paymentMethodAvailable'] = $paymentMethodAvailable;
        return $responseData;
    }

    public function updatePaymentMethod($data)
    {
        $user = auth('affiliate-api')->user();
        $user->update(['payment_method'=>$data['method'], 'payment_info'=>$data['payment_info'] ] );
        return $user;
    }

    public function updatePassword($data)
    {
        $user = auth('affiliate-api')->user();
        $userPass = \DB::table('affiliates')->where('id', $user->id)->select('password')->first();
        if (Hash::check($data['current_password'], $userPass->password)) {
            $user->password = Hash::make($data['password']);
            $user->save();
            return $this->successResponse(null, "Password updated", 200);
        }else{
            return $this->errorResponse('Current password incorrect', 400);
        }
    }

    public function updateProfile($data)
    {
        $user = auth('affiliate-api')->user();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->company = $data['company'];
        $user->address = $data['address'];
        $user->facebook = $data['facebook'];
        $user->youtube = $data['youtube'];
        $user->instagram = $data['instagram'];
        $user->twitter = $data['twitter'];
        $user->website = $data['website'];
        $user->country = $data['country'];
        $user->phone = $data['phone'];
        $user->zipcode = $data['zipcode'];
        $user->city = $data['city'];
        $user->additional_infos = $data['additional_infos'];
        $user->save();
        return $user;

    }
}
