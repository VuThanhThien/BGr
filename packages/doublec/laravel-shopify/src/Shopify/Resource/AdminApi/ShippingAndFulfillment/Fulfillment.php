<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\ShippingAndFulfillment;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Fulfillment extends BaseResource
{

    /**
     * Retrieve all collects for the shop
     * Retrieve only collects for a certain product
     * Retrieve only collects for a certain collection
     * @param int $orderId
     * @param array $params
     * @return object|array|null
     */
    public function all(int $orderId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("orders/$orderId/fulfillments.json", $params));
    }

    /**
     * @param int $fulfillmentOrderId
     * @param array $params
     * @return object|array|null
     */
    public function allOfFulfillmentOrder(int $fulfillmentOrderId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("fulfillment_orders/$fulfillmentOrderId/fulfillments.json", $params));
    }

    /**
     * @param int $orderId
     * @param array $params
     * @return object|array|null
     */
    public function count(int $orderId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("orders/$orderId/fulfillments/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $orderId
     * @param int $fulfillmentId
     * @return object|array|null
     */
    public function find(int $orderId, int $fulfillmentId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("orders/$orderId/fulfillments/$fulfillmentId.json"));
    }

    /**
     * Creates an article for a blog
     * @param int $orderId
     * @param array $params
     * @return object|array|null
     */
    public function create(int $orderId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders/$orderId/fulfillments.json", $params));
    }

    /**
     * @param array $params
     * @return object|array|null
     */
    public function createForFulfillmentOrder(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("fulfillments.json", $params));
    }

    /**
     * Updates an article
     * @param int $orderId
     * @param int $fulfillmentId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $orderId, int $fulfillmentId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("orders/$orderId/fulfillments/$fulfillmentId.json", $params));
    }

    /**
     * @param int $fulfillmentId
     * @param array $params
     * @return object|array|null
     */
    public function updateTracking(int $fulfillmentId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("fulfillments/$fulfillmentId/update_tracking.json", $params));
    }

    /**
     * @param int $orderId
     * @param int $fulfillmentId
     * @param array $params
     * @return object|array|null
     */
    public function complete(int $orderId, int $fulfillmentId, $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders/$orderId/fulfillments/$fulfillmentId/complete.json", $params));
    }

    /**
     * @param int $orderId
     * @param int $fulfillmentId
     * @param array $params
     * @return object|array|null
     */
    public function open(int $orderId, int $fulfillmentId, $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders/$orderId/fulfillments/$fulfillmentId/open.json", $params));
    }

    /**
     * @param int $orderId
     * @param int $fulfillmentId
     * @param array $params
     * @return object|array|null
     */
    public function cancelForOrder(int $orderId, int $fulfillmentId, $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders/$orderId/fulfillments/$fulfillmentId/cancel.json", $params));
    }

    /**
     * @param int $fulfillmentId
     * @param array $params
     * @return object|array|null
     */
    public function cancel(int $fulfillmentId, $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("fulfillments/$fulfillmentId/cancel.json", $params));
    }

}
