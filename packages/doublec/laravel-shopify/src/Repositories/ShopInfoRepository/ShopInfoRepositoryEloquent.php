<?php


namespace DoubleC\LaravelShopify\Repositories\ShopInfoRepository;


use Carbon\Carbon;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\ShopInfo;
use DoubleC\LaravelShopify\Repositories\BaseRepository;

class ShopInfoRepositoryEloquent extends BaseRepository implements ShopInfoRepository
{

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return ShopInfo::class;
    }

    public function updateOrCreateShopInfo(Shop $shop, array $info): ShopInfo
    {
        $si = new ShopInfo();
        $shopInfo = $shop->info()->first();
        $attributes = [];
        foreach ($info as $key => $value) {
            if (in_array($key, $si->getFillable())) {
                $attributes[$key] = $value;
            }
        }
        $attributes['description'] = base64_encode(json_encode($info));
        $attributes['created_at'] = Carbon::parse($info['created_at'])->toDateTimeString();
        $attributes['updated_at'] = Carbon::parse($info['updated_at'])->toDateTimeString();
        if (!$shopInfo) {
            $attributes['shop_id'] = $shop->id;
            $attributes['shopify_id'] = $info['id'];
            return ShopInfo::create($attributes);
        } else {
            $shopInfo->update($attributes);
            return $shopInfo;
        }
    }
}
