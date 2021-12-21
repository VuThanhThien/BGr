<?php


namespace DoubleC\LaravelShopify\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static set(string $key, mixed $value, int $shop_id = null)
 * @method static get(string $key, mixed $default, int $shop_id = null)
 * @method static has(string $key, int $shop_id = null)
 * @method static delete(string $key, int $shop_id = null)
 * @method static clear(int $shop_id = null)
 */
class ShopSetting extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'shop.setting';
    }
}
