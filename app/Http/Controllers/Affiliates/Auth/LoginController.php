<?php

namespace App\Http\Controllers\Affiliates\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use DoubleC\LaravelShopify\Traits\Authenticator;
use Illuminate\Auth\Access\AuthorizationException;

class LoginController extends Controller
{

    use Authenticator;


    public function getLogin(Request $request)
    {
        $origin = $request->header('origin');
        $subdomain = getSubdomainFromUrl($origin);
        $setting = ShopSetting::where('sub_domain', $subdomain)->whereHas('shop', function ($query) {
            $query->whereNotNull('access_token');
        })->first();

        if($setting) {

            $responseData['logo'] = $setting->logo;
            $responseData['brand_name'] = $setting->brand_name;
            $responseData['money_format'] = $setting->info->money_format;
            $responseData['is_enable_term_policy'] = $setting->is_enable_term_policy;
            $responseData['default_affiliate_language'] = $setting->default_affiliate_language;
            $responseData['enable_affiliate_language'] = $setting->enable_affiliate_language;
            $responseData['auto_detect_language'] = $setting->auto_detect_language;
            $responseData['login_page_config'] = $setting->login_page_config;

            return $this->successResponse($responseData, 'success', 200);

        }else{
            return $this->errorResponse('not found', 404);
        }
    }

    public function login(Request $request)
    {
        $origin = $request->header('origin');
        $subdomain = getSubdomainFromUrl($origin);
        $shopSetting = ShopSetting::where('sub_domain', $subdomain)->whereHas('shop', function ($query) {
            $query->whereNotNull('access_token');
        })->first();

        \Illuminate\Support\Facades\App::setLocale($shopSetting->default_affiliate_language);

        if($shopSetting)
        {
            $shop_id = $shopSetting->shop_id;
            $checkAffiliateExist = Affiliate::where('shop_id',$shop_id)->where('email',$request->email);
            if($checkAffiliateExist->count()>0)
            {
                if ($token = auth('affiliate-api')->attempt($this->credentials($request,$shop_id))) {
                    return response()->json([
                        'affiliate' => auth('affiliate-api')->user(),
                        'token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => auth()->factory()->getTTL() * 60
                    ]);
                }

            }
        }
        throw new AuthorizationException("invalid_credential");
    }

    public function verify(Request $request)
    {
        $user = auth('affiliate-api')->user();
        return response()->json([
            'affiliate' => $user,
            'token' => $request->bearerToken(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    protected function credentials(Request $request,$shop_id)
{
    return array_merge($request->only('email', 'password'), ['shop_id' => $shop_id]);
}
}
