<?php

namespace DoubleC\LaravelShopify\Providers;

use DoubleC\LaravelShopify\Services\ChargeService\ChargeService;
use DoubleC\LaravelShopify\Services\ChargeService\ChargeServiceImp;
use DoubleC\LaravelShopify\Services\SettingService\SettingService;
use DoubleC\LaravelShopify\Services\SettingService\SettingServiceImp;
use DoubleC\LaravelShopify\Services\ShopService\ShopService;
use DoubleC\LaravelShopify\Services\ShopService\ShopServiceImp;
use DoubleC\LaravelShopify\Services\SubscriptionHistoryService\SubscriptionHistoryImp;
use DoubleC\LaravelShopify\Services\SubscriptionHistoryService\SubscriptionHistoryService;
use DoubleC\LaravelShopify\Services\ThemeService\ThemeService;
use DoubleC\LaravelShopify\Services\ThemeService\ThemeServiceImp;
use DoubleC\LaravelShopify\Services\WebhookService\WebhookService;
use DoubleC\LaravelShopify\Services\WebhookService\WebhookServiceImp;
use Illuminate\Support\ServiceProvider;

class DoublecServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function register()
    {
        $services = [
            ShopService::class => ShopServiceImp::class,
            SubscriptionHistoryService::class => SubscriptionHistoryImp::class,
            ChargeService::class => ChargeServiceImp::class,
            WebhookService::class => WebhookServiceImp::class,
            SettingService::class => SettingServiceImp::class,
            ThemeService::class => ThemeServiceImp::class
        ];
        foreach ($services as $service => $implementing) {
            $this->app->bind($service, $implementing);
        }
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
    }
}
