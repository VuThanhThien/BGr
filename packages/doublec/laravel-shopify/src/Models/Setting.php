<?php

namespace DoubleC\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'shop_id',
        'key',
        'value',
    ];

    /**
     * @return BelongsTo|Shop
     */
    public function shop(): BelongsTo|Shop
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * @param Builder $query
     * @param int $shop_id
     * @param string $key
     * @return Builder
     */
    public function scopeForShop(Builder $query, int $shop_id, string $key): Builder
    {
        return $query->where("shop_id", $shop_id)->where("key", $key);
    }
}
