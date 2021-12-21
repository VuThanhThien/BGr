<?php

namespace App\Http\Controllers\Affiliates\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\ShopSetting;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request) {

        $request->validate(['email' => 'required|email']);  
                $origin = $request->header('origin');
        $subdomain = getSubdomainFromUrl($origin);
        $shopSetting = ShopSetting::where('sub_domain', $subdomain)->whereHas('shop', function ($query) {
            $query->whereNotNull('access_token');
        })->first();
       $shop_id = $shopSetting->shop_id;         
        $status=Password::sendResetLink($this->credentials($request->only('email'),$shop_id));
        
        $checkStatus=$status === Password::RESET_LINK_SENT;
        return $checkStatus? response()->json([
            "status"=>true,
            "msg" => __($status)]
            )
        : response()->json([
            "status"=>false,
            "msg" =>__($status)]
        );
    }
    public function reset(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'confirmed|min:8',
        ]);
        $origin = $request->header('origin');
        $subdomain = getSubdomainFromUrl($origin);
        $shopSetting = ShopSetting::where('sub_domain', $subdomain)->whereHas('shop', function ($query) {
            $query->whereNotNull('access_token');
        })->first();
       $shop_id = $shopSetting->shop_id;
        $reset_password_status = Password::reset($this->credentials($credentials,$shop_id), function ($user, $password) {
            $user->password =Hash::make($password);
            $user->save();
        });
        if ($reset_password_status == Password::INVALID_USER) {
            return 
            response()->json([
                "status"=>false,
                "msg" => "Invalid Credentials"]
            );
        }
        if ($reset_password_status == Password::INVALID_TOKEN) {
            return 
            response()->json([
                "status"=>false,
                "msg" => "Invalid token provided"]
            );
        }
        return  response()->json([
            "status"=>true,
            "msg" => "Password has been successfully changed"
            ]
        );
    }
    protected function credentials($data,$shop_id)
    {
        return array_merge($data, ['shop_id' => $shop_id]);
    }
}
