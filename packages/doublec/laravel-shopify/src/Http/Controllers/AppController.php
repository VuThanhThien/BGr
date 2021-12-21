<?php

namespace DoubleC\LaravelShopify\Http\Controllers;

use App\Http\Controllers\Controller;
use DoubleC\LaravelShopify\Events\AppInstalledEvent;
use DoubleC\LaravelShopify\Http\Requests\InstallAppRequest;
use DoubleC\LaravelShopify\HttpClient\HttpClientApi;
use DoubleC\LaravelShopify\Services\AuthenticateApi\AuthenticateApi;
use DoubleC\LaravelShopify\Shopify\Authentication\AuthenticationImp;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Billing\RecurringApplicationCharge;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\StoreProperties\Shop;
use DoubleC\LaravelShopify\Traits\Authenticator;
use DoubleC\LaravelShopify\Traits\ShopCharge;
use DoubleC\LaravelShopify\Traits\ShopSubscription;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use Authenticator;
    use ShopSubscription;
    use ShopCharge;

    protected $jwtToken;

    /**
     * Show view to install app
     * @return Application|Factory|View
     */
    public function install(): Factory|View|Application
    {
        return view("laravel-shopify::pages.install");
    }

    /**
     * Install application
     * @param InstallAppRequest $request
     * @return RedirectResponse
     */
    public function postInstall(InstallAppRequest $request): RedirectResponse
    {
        $shopName = shopNameFromDomain($request->get("shop"));
        return $this->installApp($shopName);
    }

    /**
     * When install success app
     * @param Request $request
     * @return RedirectResponse
     */
    public function authorizeApp(Request $request)
    {
        $code = $request->get('code');
        $domain = $request->get('shop');
        $shopName = shopNameFromDomain($domain);
        $shop = $this->shopRepository->findByShopDomain($domain);
        if ($this->shopService->isInstalled($shop)) {
            return $this->login($domain);
        }
        /** @var AuthenticateApi $authenticateApi */
        $authenticateApi = app(AuthenticateApi::class);
        $accessToken = $authenticateApi->forShopName($shopName)->accessToken($code);
        if (!$accessToken) return $this->installApp($shopName);
        if ($shop) {
            # Reinstall app (Charge relationship)
            if (!$this->shopService->isUninstalled($shop)) {
                $this->shopService->unInstallApp($shop);
            }
            $shop = $this->shopService->installApp($shop, $accessToken);
        } else {
            $shop = $this->shopRepository->create([
                'shop' => $domain,
                'access_token' => $accessToken,
                'installed_at' => now()
            ]);
        }
        
        $shopInfo = $this->shopService->pullShopInfo($shop);
        $user = $this->userRepository->findOrCreateByShop($shop);
        $this->userRepository->updateFromShopInfo($user, $shopInfo);
        
        $this->jwtToken = auth()->login($user);

        $this->clientApi()->setToken($accessToken);
        $this->clientApi()->setShopName($shopName);
        session(['client_api' => $this->clientApi()]);
        $this->webhookService->registerUninstallAppWebhook($shop, route('dc.app-uninstalled-webhook'));
        event(new AppInstalledEvent($shop));
        return $this->billing();
    }

    /**
     * Login from Shopify Admin
     * @param Request $request
     * @return RedirectResponse
     */
    public function loginShopify(Request $request): RedirectResponse
    {
        $domain = $request->get('shop');
        return $this->login($domain);
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function getToken(Request $request): string|null
    {
        $shopName = $request->get('shop');
        $httpClient = new HttpClientApi();
        $httpClient->setVerifyPeer(false);
        $httpClient->setAccessToken('');
        $authenticate = new AuthenticationImp($httpClient);
        return $authenticate->forShopName($shopName)
            ->usingClientId(config('shopify.api_key'))
            ->usingClientSecret(config('shopify.shared_secret'))
            ->toPermanentToken('43f86b59954b7ad4f9c2d0c3ad4d4b8d');
    }

    /**
     * When active success
     * @param Request $request
     * @return View|Factory|RedirectResponse|Application
     */
    public function activeCharge(Request $request): View|Factory|RedirectResponse|Application
    {
        if (auth()->check()) {
            $recurringApplicationCharge = new RecurringApplicationCharge(generateClientApi($this->shop()));
            $charge = $recurringApplicationCharge->find($request->get('charge_id'));
            $shop = $this->shop();
            $plan = session('plan');
            if ($charge && $plan) {
                $preparedData = [
                    'charge' => $charge['recurring_application_charge']
                ];
                if (session('coupon')) {
                    $preparedData['coupon'] = session('coupon');
                }
                $this->subscriptionHistoryService->subscribe($shop, $plan, $preparedData);
                return redirect()->to(config('shopify.activated_redirect_link'));
            }
        }
        return view('laravel-shopify::pages.errors', [
            "messages" => [
                "Active charge failed, please double check again !"
            ]
        ]);
    }
}
