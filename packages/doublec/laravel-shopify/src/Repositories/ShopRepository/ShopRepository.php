<?php


namespace DoubleC\LaravelShopify\Repositories\ShopRepository;


use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Repositories\Repository;

interface ShopRepository extends Repository
{
    /**
     * Find shop by domain
     * @param string $domain
     * @return Shop|null
     */
    public function findByShopDomain(string $domain): ?Shop;

    /**
     * @param int $shopId
     * @return Plan
     */
    public function getShopPlan(int $shopId): Plan;



}
