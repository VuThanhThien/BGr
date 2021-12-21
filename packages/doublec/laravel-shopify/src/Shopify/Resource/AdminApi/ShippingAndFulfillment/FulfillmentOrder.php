<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\ShippingAndFulfillment;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class FulfillmentOrder extends BaseResource
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
        return $this->prepareResponse($this->shopifyApi->get("orders/$orderId/fulfillment_orders.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $fulfillmentId
     * @return object|array|null
     */
    public function find(int $fulfillmentId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("fulfillment_orders/$fulfillmentId.json"));
    }

    /**
     * Creates an article for a blog
     * @param int $fulfillmentOrderId
     * @param array $params
     * @return object|array|null
     */
    public function cancel(int $fulfillmentOrderId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("fulfillment_orders/$fulfillmentOrderId/cancel.json", $params));
    }

    /**
     * Creates an article for a blog
     * @param int $fulfillmentOrderId
     * @param array $params
     * @return object|array|null
     */
    public function close(int $fulfillmentOrderId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("fulfillment_orders/$fulfillmentOrderId/close.json", $params));
    }

    /**
     * Creates an article for a blog
     * @param int $fulfillmentOrderId
     * @param array $params
     * @return object|array|null
     */
    public function move(int $fulfillmentOrderId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("fulfillment_orders/$fulfillmentOrderId/move.json", $params));
    }

    /**
     * Creates an article for a blog
     * @param int $fulfillmentOrderId
     * @param array $params
     * @return object|array|null
     */
    public function open(int $fulfillmentOrderId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("fulfillment_orders/$fulfillmentOrderId/open.json", $params));
    }

    /**
     * Creates an article for a blog
     * @param int $fulfillmentOrderId
     * @param array $params
     * @return object|array|null
     */
    public function reschedule(int $fulfillmentOrderId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("fulfillment_orders/$fulfillmentOrderId/reschedule.json", $params));
    }

}
