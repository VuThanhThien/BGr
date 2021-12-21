<?php

namespace App\Http\Controllers\Merchants\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DoubleC\LaravelShopify\Traits\Authenticator;

class LoginController extends Controller
{

    use Authenticator;

    public function initialHomePage($token, $isSkipStarted, bool $sudo = false)
    {
        $theme = $this->themeService->pullThemeInfo($this->shop());
        if ($theme) {
            session(['theme_id' => $theme['id']]);
            session(['theme_name' => $theme['role']]);
            setting(['theme_id' => $theme['id']]);
            if ($sudo) session(['sudo' => $sudo]);
        }
        return view('app', ['token'=>$token, 'isSkipStarted' => $isSkipStarted]);
    }

    public function loginFromShopify(Request $request)
    {
        $domain = $request->get('shop');
        $shopName = shopNameFromDomain($domain);
        $shop = $this->shopRepository->findByShopDomain($domain);
        if (!$shop || $this->shopService->isUninstalled($shop)) {
            return $this->installApp($shopName);
        }
        $this->clientApi()->setShopName($shopName);
        $this->clientApi()->setToken($shop->access_token);
        $shopInfo = $this->shopService->pullShopInfo($shop);
        if (!$shopInfo) return $this->installApp($shopName);
        $user = $this->userRepository->findByShopName($shopName);
        $setting = $user->setting()->select('id', 'shop_id', 'is_skip_started')->first();
        $this->userRepository->updateFromShopInfo($user, $shopInfo);
        /** @var Shop $shopApi */
        session(['client_api' => $this->clientApi()]);
        $token = auth()->login($user);
        // event(new AppLoggedEvent($shop));
        # More action
        return $this->initialHomePage($token, $setting->is_skip_started);
    }

    

    public function verify(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'token' => $request->bearerToken(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
