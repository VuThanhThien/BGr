<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\ShippingAndFulfillment;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class FulfillmentEvent extends BaseResource
{

    /**
     * Retrieve all collects for the shop
     * Retrieve only collects for a certain product
     * Retrieve only collects for a certain collection
     * @param int $orderId
     * @param int $fulfillmentId
     * @param array $params
     * @return object|array|null
     */
    public function all(int $orderId, int $fulfillmentId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("orders/$orderId/fulfillments/$fulfillmentId/events.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $orderId
     * @param int $fulfillmentId
     * @param int $eventId
     * @return object|array|null
     */
    public function find(int $orderId, int $fulfillmentId, int $eventId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("orders/$orderId/fulfillments/$fulfillmentId/events/$eventId.json"));
    }

    /**
     * Creates an article for a blog
     * @param int $orderId
     * @param int $fulfillmentId
     * @param array $params
     * @return object|array|null
     */
    public function create(int $orderId, int $fulfillmentId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders/$orderId/fulfillments/$fulfillmentId/events.json", $params));
    }


    /**
     * Deletes an article
     * @param int $orderId
     * @param int $fulfillmentId
     * @param int $eventId
     * @return object|array|null
     */
    public function delete(int $orderId, int $fulfillmentId, int $eventId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("orders/$orderId/fulfillments/$fulfillmentId/events/$eventId.json")
        );
    }

}
