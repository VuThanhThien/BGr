<?php

namespace DoubleC\LaravelShopify\Providers;

use DoubleC\LaravelShopify\Repositories\ChargeRepository\ChargeRepository;
use DoubleC\LaravelShopify\Repositories\ChargeRepository\ChargeRepositoryEloquent;
use DoubleC\LaravelShopify\Repositories\CouponRepository\CouponRepository;
use DoubleC\LaravelShopify\Repositories\CouponRepository\CouponRepositoryEloquent;
use DoubleC\LaravelShopify\Repositories\DiscountRepository\DiscountRepository;
use DoubleC\LaravelShopify\Repositories\DiscountRepository\DiscountRepositoryEloquent;
use DoubleC\LaravelShopify\Repositories\FeatureRepository\FeatureRepository;
use DoubleC\LaravelShopify\Repositories\FeatureRepository\FeatureRepositoryEloquent;
use DoubleC\LaravelShopify\Repositories\PlanRepository\PlanRepository;
use DoubleC\LaravelShopify\Repositories\PlanRepository\PlanRepositoryEloquent;
use DoubleC\LaravelShopify\Repositories\ShopInfoRepository\ShopInfoRepository;
use DoubleC\LaravelShopify\Repositories\ShopInfoRepository\ShopInfoRepositoryEloquent;
use DoubleC\LaravelShopify\Repositories\ShopRepository\ShopRepository;
use DoubleC\LaravelShopify\Repositories\ShopRepository\ShopRepositoryEloquent;
use DoubleC\LaravelShopify\Repositories\SubscriptionHistoryRepository\SubscriptionHistoryRepository;
use DoubleC\LaravelShopify\Repositories\SubscriptionHistoryRepository\SubscriptionHistoryRepositoryEloquent;
use DoubleC\LaravelShopify\Repositories\UserRepository\UserRepository;
use DoubleC\LaravelShopify\Repositories\UserRepository\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class DoublecRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function register()
    {
        $repositories = [
            ShopRepository::class => ShopRepositoryEloquent::class,
            ShopInfoRepository::class => ShopInfoRepositoryEloquent::class,
            UserRepository::class => UserRepositoryEloquent::class,
            PlanRepository::class => PlanRepositoryEloquent::class,
            ChargeRepository::class => ChargeRepositoryEloquent::class,
            SubscriptionHistoryRepository::class => SubscriptionHistoryRepositoryEloquent::class,
            CouponRepository::class => CouponRepositoryEloquent::class,
            DiscountRepository::class => DiscountRepositoryEloquent::class,
            FeatureRepository::class => FeatureRepositoryEloquent::class
        ];
        foreach ($repositories as $repository => $implementing) {
            $this->app->bind($repository, $implementing);
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
