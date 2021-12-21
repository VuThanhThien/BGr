<?php

namespace DoubleC\LaravelShopify\Providers;

use DoubleC\LaravelShopify\Services\SettingService\SettingService;
use Illuminate\Support\ServiceProvider;

class ShopSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function register()
    {
        $this->app->bind('shop.setting', function () {
            return app(SettingService::class);
        });
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
    }
}
