<?php

namespace DoubleC\LaravelShopify\Repositories\CouponRepository;

use DoubleC\LaravelShopify\Models\Coupon;
use DoubleC\LaravelShopify\Repositories\BaseRepository;

class CouponRepositoryEloquent extends BaseRepository implements CouponRepository {

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Coupon::class;
    }
}
