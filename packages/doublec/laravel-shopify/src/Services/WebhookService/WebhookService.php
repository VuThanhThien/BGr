<?php


namespace DoubleC\LaravelShopify\Services\WebhookService;


use DoubleC\LaravelShopify\Models\Shop;

interface WebhookService
{
    /**
     * Register webhook for topic 'app/uninstalled'
     * @param Shop $shop
     * @param string $url
     * @param string $format
     * @return bool
     */
    public function registerUninstallAppWebhook(Shop $shop, string $url, string $format = 'json'): bool;
}
