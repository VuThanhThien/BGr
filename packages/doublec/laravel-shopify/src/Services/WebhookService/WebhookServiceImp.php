<?php


namespace DoubleC\LaravelShopify\Services\WebhookService;


use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Events\Webhook;
use Exception;

class WebhookServiceImp implements WebhookService
{
    public function registerUninstallAppWebhook(Shop $shop, string $url, string $format = 'json'): bool
    {
        try {
            $client = generateClientApi($shop);
            $webhookResource = new Webhook($client);
            $response = $webhookResource->create([
                "webhook" => [
                    "topic" => 'app/uninstalled',
                    "address" => $url,
                    "format" => $format
                ]
            ]);
            return isset($response['webhook']);
        } catch (Exception $exception) {
            logger()->error($exception->getMessage());
            return false;
        }
    }
}
