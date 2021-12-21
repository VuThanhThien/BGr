<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Events;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Webhook extends BaseResource
{

    /**
     * Retrieve a list of all webhook subscriptions for your shop
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        return $this->prepareResponse($this->shopifyApi->get('webhooks.json', $params));
    }

    /**
     * Count all of the webhook subscriptions for your shop
     * @param array $params
     * @return array
     */
    public function count(array $params = []): array
    {
        return $this->prepareResponse($this->shopifyApi->get('webhooks/count.json', $params));
    }

    /**
     * Retrieves a single webhook subscription
     * @param int $webhookId
     * @return array
     */
    public function find(int $webhookId): array
    {
        return $this->prepareResponse($this->shopifyApi->get("webhooks/$webhookId.json"));
    }

    /**
     * Subscribe to order creation webhooks
     * @param array $params
     * @return array
     */
    public function create(array $params = []): array
    {
        return $this->prepareResponse($this->shopifyApi->post('webhooks.json', $params));
    }

    /**
     * Update a webhook subscription's topic or address URIs
     * @param int $webhookId
     * @param array $params
     * @return array
     */
    public function update(int $webhookId, array $params): array
    {
        return $this->prepareResponse($this->shopifyApi->put("webhooks/$webhookId.json", $params));
    }

    /**
     * Delete a webhook subscription
     * @param int $webhookId
     * @return array
     */
    public function delete(int $webhookId): array
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("webhooks/$webhookId.json")
        );
    }

}
