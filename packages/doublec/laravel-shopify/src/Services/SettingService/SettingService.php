<?php


namespace DoubleC\LaravelShopify\Services\SettingService;

interface SettingService
{
    /**
     * Update or create setting
     * @param string $key
     * @param mixed $value
     * @param int|null $shop_id
     * @return bool
     */
    public function set(string $key, mixed $value, int $shop_id = null): bool;

    /**
     * Get shop setting by key
     * @param string $key
     * @param mixed $default
     * @param int|null $shop_id
     * @return mixed
     */
    public function get(string $key, mixed $default, int $shop_id = null): mixed;

    /**
     * Check exists setting by key
     * @param string $key
     * @param int|null $shop_id
     * @return bool
     */
    public function has(string $key, int $shop_id = null): bool;

    /**
     * Delete setting by key
     * @param string $key
     * @param int|null $shop_id
     * @return bool
     */
    public function delete(string $key, int $shop_id = null): bool;

    /**
     * Clear all setting by shop id
     * @param int|null $shop_id
     * @return bool
     */
    public function clear(int $shop_id = null): bool;
}
