<?php

namespace DoubleC\LaravelShopify\Http\Controllers;

use DoubleC\LaravelShopify\Events\AppUninstalledEvent;
use DoubleC\LaravelShopify\Repositories\ShopRepository\ShopRepository;
use DoubleC\LaravelShopify\Services\ShopService\ShopService;
use DoubleC\LaravelShopify\Services\SubscriptionHistoryService\SubscriptionHistoryService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * WebhookController constructor.
     * @param ShopRepository $shopRepository
     * @param ShopService $shopService
     * @param SubscriptionHistoryService $subscriptionHistoryService
     */
    public function __construct(
        # Repositories
        public ShopRepository $shopRepository,
        # Service
        public ShopService $shopService,
        public SubscriptionHistoryService $subscriptionHistoryService
    )
    {
    }

    /**
     * Process when app uninstalled [Shopify webhook]
     * @param Request $request
     */
    public function appUninstalledWebhook(Request $request)
    {
        $content = $request->getContent();
        $domain = json_decode($content)->myshopify_domain;
        $shop = $this->shopRepository->findByShopDomain($domain);
        if (!$shop) logger()->warning("{$domain} | Uninstall failed with content: {$content}");
        event(new AppUninstalledEvent($shop));
        $this->shopService->unInstallApp($shop);
        $this->subscriptionHistoryService->unsubscribe($shop);
    }
}
