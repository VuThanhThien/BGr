<?php


namespace DoubleC\LaravelShopify\Repositories\UserRepository;


use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\ShopInfo;
use App\Models\User;
use DoubleC\LaravelShopify\Repositories\BaseRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{

    public function getModel(): string
    {
        return User::class;
    }

    public function findOrCreateByShop(Shop $shop): User
    {
        return User::firstOrCreate([
            'shop_id' => $shop->id,
            'shop_name' => shopNameFromDomain($shop->shop),
        ]);
    }

    public function findByDomain(string $domain): ?User
    {
        return User::whereShopName(shopNameFromDomain($domain))->first();
    }

    public function updateFromShopInfo(User $user, ShopInfo $shopInfo): bool
    {
        $user->name = $shopInfo->shop_owner;
        $user->email = $shopInfo->email;
        $user->plan_name = $shopInfo->plan_name;
        $user->save();
        return true;
    }

    public function findByShopName(string $shopName): ?User
    {
        return User::whereShopName($shopName)->first();
    }
}
