<?php


namespace DoubleC\LaravelShopify\Repositories\ShopRepository;


use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Repositories\BaseRepository;

class ShopRepositoryEloquent extends BaseRepository implements ShopRepository
{
    /**
     * @return string
     */
    public function getModel(): string
    {
        return Shop::class;
    }

    public function findByShopDomain(string $domain): ?Shop
    {
        return $this->model->whereShop($domain)->first();
    }

    public function getShopPlan(int $shopId): Plan
    {
        // TODO: Implement getShopPlan() method.
    }
}
