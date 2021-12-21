<?php


namespace DoubleC\LaravelShopify\Repositories\UserRepository;


use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\ShopInfo;
use App\Models\User;
use DoubleC\LaravelShopify\Repositories\Repository;

interface UserRepository extends Repository
{
    /**
     * Find or create user by shop info
     * @param Shop $shop
     * @return User
     */
    public function findOrCreateByShop(Shop $shop): User;

    /**
     * Find User by shopify domain
     * @param string $domain
     * @return User|null
     */
    public function findByDomain(string $domain): ?User;

    /**
     * Find User by shopify domain
     * @param string $shopName
     * @return User|null
     */
    public function findByShopName(string $shopName): ?User;

    /**
     * Update user from shopify shop info
     * @param User $user
     * @param ShopInfo $shopInfo
     * @return bool
     */
    public function updateFromShopInfo(User $user, ShopInfo $shopInfo): bool;
}
