<?php


namespace DoubleC\LaravelShopify\Services\SettingService;

use DoubleC\LaravelShopify\Models\Setting;
use DoubleC\LaravelShopify\Traits\InstalledShop;
use Exception;

class SettingServiceImp implements SettingService
{
    use InstalledShop;

    public function set(string $key, mixed $value, int $shop_id = null): bool
    {
        $shop_id = $shop_id ? $shop_id : $this->shopId();
        $setting = Setting::forShop($shop_id, $key)->first();
        if ($setting) return Setting::forShop($shop_id, $key)->update(["value" => $value]);
        $setting = Setting::create(["shop_id" => $shop_id, "key" => $key, "value" => $value]);
        if ($setting && $setting->exists) return true;
        return false;
    }

    public function get(string $key, mixed $default, int $shop_id = null): mixed
    {
        if ($shop_id) {
            $setting = Setting::where([
                "shop_id" => $shop_id,
                "key" => $key
            ])->first();
        } else {
            # Session
            $setting = $this->shop()->settings()->where('key', $key)->first();
        }
        return $setting ? $setting->value : $default;
    }

    public function has(string $key, int $shop_id = null): bool
    {
        $shop_id = $shop_id ? $shop_id : $this->shopId();
        return (bool)Setting::forShop($shop_id, $key)->first();
    }

    public function delete(string $key, int $shop_id = null): bool
    {
        $shop_id = $shop_id ? $shop_id : $this->shopId();
        $setting = Setting::forShop($shop_id, $key)->first();
        try {
            if (!$setting) return true;
            return (bool)Setting::forShop($shop_id, $key)->delete();
        } catch (Exception) {
            return false;
        }
    }

    public function clear(int $shop_id = null): bool
    {
        try {
            $shop_id = $shop_id ? $shop_id : $this->shopId();
            return (bool)Setting::whereShopId($shop_id)->delete();
        } catch (Exception) {
            return false;
        }
    }
}
