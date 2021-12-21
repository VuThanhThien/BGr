<?php


namespace DoubleC\LaravelShopify\Traits;

use DoubleC\LaravelShopify\Events\AppInstalledEvent;
use DoubleC\LaravelShopify\Events\AppLoggedEvent;
use DoubleC\LaravelShopify\Services\AuthenticateApi\AuthenticateApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\StoreProperties\Shop;
use Exception;
use Illuminate\Http\RedirectResponse;
use Ramsey\Uuid\Uuid;
use RuntimeException;

trait Authenticator
{
    use InstalledShop;

    /**
     * Send install app request to Shopify.
     * @param string $shopName
     * @return RedirectResponse
     */
    public function installApp(string $shopName): RedirectResponse
    {
        try {
            $state = Uuid::uuid4()->toString();
        } catch (Exception) {
            $state = date("YmdHis", time());
        }
        session(['state' => $state]);
        try {
            /** @var AuthenticateApi $authenticateApi */
            $authenticateApi = app(AuthenticateApi::class);
            return $authenticateApi->forShopName($shopName)->initiateLogin($state);
        } catch (RuntimeException $e) {
            return redirect()->route('install')->with(["message" => $e->getMessage()]);
        }
    }

    /**
     * Login from shopify admin
     * @param string $domain
     * @param bool $sudo
     * @return RedirectResponse
     */
    public function login(string $domain, bool $sudo = false): RedirectResponse
    {
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
        $this->userRepository->updateFromShopInfo($user, $shopInfo);
        /** @var Shop $shopApi */
        session(['client_api' => $this->clientApi()]);
        auth()->login($user);
        event(new AppLoggedEvent($shop));
        # More action
        return $this->initialHomePage($sudo);
    }

    /**
     * Logout, clear session
     */
    public function logout()
    {
        session()->forget([
            'theme_id', 'theme_name', 'sudo', 'state', 'authenticate_api', 'coupon'
        ]);
        auth()->logout();
    }
}
