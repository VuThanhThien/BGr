<?php

namespace DoubleC\LaravelShopify;

use DoubleC\LaravelShopify\Http\Middleware\ActivateShopMiddleware;
use DoubleC\LaravelShopify\Http\Middleware\AdminShopMiddleware;
use DoubleC\LaravelShopify\Http\Middleware\ShopifyValidWebhookMiddleware;
use DoubleC\LaravelShopify\Http\Middleware\ShopAuthenticateMiddleware;
use DoubleC\LaravelShopify\Http\Middleware\ShopifyRequestMiddleware;
use DoubleC\LaravelShopify\Providers\DoublecRepositoryProvider;
use DoubleC\LaravelShopify\Providers\DoublecServiceProvider;
use DoubleC\LaravelShopify\Providers\ShopSettingServiceProvider;
use DoubleC\LaravelShopify\Services\AuthenticateApi\AuthenticateApi;
use DoubleC\LaravelShopify\Services\AuthenticateApi\AuthenticateImp;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class LaravelShopifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        # Boot facades
        $this->app->register(ShopSettingServiceProvider::class);
        # Service provider
        $this->app->register(DoublecServiceProvider::class);
        # Repositories provider
        $this->app->register(DoublecRepositoryProvider::class);
        # Authenticate binding
        $this->app->bind(AuthenticateApi::class, function () {
            return $this->authenticateApi();
        });
        $this->app->bind(ClientApi::class, function () {
            return $this->clientApi();
        });
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        # Load Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        # Load Migration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        # Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'laravel-shopify');
        # Publish assets
        $this->publishAssets();
        # Publish configs
        $this->publishConfigs();
        # Push middleware
        $this->registerMiddleware($this->app['router']);
    }

    /**
     * |-----------------------------------
     * |-----------------------------------
     * Publish assets
     * @return void
     */
    public function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendors/double-c')
        ], 'public');
    }

    /**
     * |-----------------------------------
     * |-----------------------------------
     * Publish configs
     * @return void
     */
    public function publishConfigs()
    {
        $this->publishes([
            __DIR__ . '/../config/shopify.php' => config_path('shopify.php'),
        ]);
    }

    /**
     * Register all configs of this package
     */
    public function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/shopify.php', 'shopify'
        );
    }

    /**
     * Register middleware
     * @param Router $router
     */
    public function registerMiddleware(Router $router)
    {
        $routeMiddleware = [
            'double-c-shop-auth' => ShopAuthenticateMiddleware::class,
            'double-c-shopify-request' => ShopifyRequestMiddleware::class,
            'double-c-activate-shop' => ActivateShopMiddleware::class,
            'double-c-valid-webhook' => ShopifyValidWebhookMiddleware::class,
            'double-c-shop-admin' => AdminShopMiddleware::class,
        ];
        foreach ($routeMiddleware as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }

    /**
     * @return AuthenticateApi
     */
    protected function authenticateApi(): AuthenticateApi
    {
        $authenticateApi = session('authenticate_api');
        if (!$authenticateApi) { // session is expired
            $authenticateApi = new AuthenticateImp();
            session(['authenticate_api' => $authenticateApi]);
        }
        return $authenticateApi;
    }

    protected function clientApi(): ClientApi
    {
        $clientApi = session('client_api');
        if (!$clientApi) { // session is expired
            $clientApi = new ClientApi();
            session(['client_api' => $clientApi]);
        }
        return $clientApi;
    }
}
