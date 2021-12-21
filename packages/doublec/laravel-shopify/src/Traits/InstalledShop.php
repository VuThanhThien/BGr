<?php


namespace DoubleC\LaravelShopify\Traits;

use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Repositories\DiscountRepository\DiscountRepository;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;
use DoubleC\LaravelShopify\Repositories\PlanRepository\PlanRepository;
use DoubleC\LaravelShopify\Repositories\ShopRepository\ShopRepository;
use DoubleC\LaravelShopify\Repositories\SubscriptionHistoryRepository\SubscriptionHistoryRepository;
use DoubleC\LaravelShopify\Repositories\UserRepository\UserRepository;
use DoubleC\LaravelShopify\Services\ChargeService\ChargeService;
use DoubleC\LaravelShopify\Services\ShopService\ShopService;
use DoubleC\LaravelShopify\Services\SubscriptionHistoryService\SubscriptionHistoryService;
use DoubleC\LaravelShopify\Services\ThemeService\ThemeService;
use DoubleC\LaravelShopify\Services\WebhookService\WebhookService;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use Illuminate\Http\RedirectResponse;

trait InstalledShop
{
    /**
     * InstalledShop constructor.
     * @param ShopService $shopService
     * @param WebhookService $webhookService
     * @param SubscriptionHistoryService $subscriptionHistoryService
     * @param ThemeService $themeService
     * @param ShopRepository $shopRepository
     * @param PlanRepository $planRepository
     * @param SubscriptionHistoryRepository $subscriptionHistoryRepository
     * @param UserRepository $userRepository
     * @param ChargeService $chargeService
     * @param DiscountRepository $discountRepository
     */
    public function __construct(
        # Services
        protected ShopService $shopService,
        protected WebhookService $webhookService,
        protected SubscriptionHistoryService $subscriptionHistoryService,
        protected ThemeService $themeService,
        # Repositories
        protected ShopRepository $shopRepository,
        protected PlanRepository $planRepository,
        protected SubscriptionHistoryRepository $subscriptionHistoryRepository,
        protected UserRepository $userRepository,
        protected ChargeService $chargeService,
        protected DiscountRepository $discountRepository
    )
    {

    }

    /**
     * Client Api
     * @return ClientApi
     */
    public function clientApi(): ClientApi
    {
        return app(ClientApi::class);
    }

    /**
     * Get shop model
     * @return Shop|null
     */
    public function shop(): Shop|null
    {
        return $this->shopRepository->find($this->shopId());
    }

    /**
     * Get shop id
     * @return int
     */
    public function shopId(): int
    {
        if (auth()->check()) {
            return auth()->user()->shop_id;
        } else {
            return false;
        }
    }

    /**
     * Get latest plan subscription (current plan)
     * @return SubscriptionHistory|null
     */
    public function shopPlan(): ?SubscriptionHistory
    {
        return $this->subscriptionHistoryService->getLatestSubscription($this->shop());
    }

    /**
     * Initial homepage
     * @param bool $sudo
     * @return RedirectResponse
     */
    public function initialHomePage(bool $sudo = false): RedirectResponse
    {
        $theme = $this->themeService->pullThemeInfo($this->shop());
        if ($theme) {
            session(['theme_id' => $theme['id']]);
            session(['theme_name' => $theme['role']]);
            setting(['theme_id' => $theme['id']]);
            if ($sudo) session(['sudo' => $sudo]);
        }
        return redirect('/');
    }
}
