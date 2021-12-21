<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Orders;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Order extends BaseResource
{

    /**
     * Retrieve all collects for the shop
     * Retrieve only collects for a certain product
     * Retrieve only collects for a certain collection
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("orders.json", $params));
    }

    /**
     * Get a count of all blogs
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("orders/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $orderId
     * @return object|array|null
     */
    public function find(int $orderId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("orders/$orderId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders.json", $params));
    }

    /**
     * @param int $orderId
     * @param array $params
     * @return object|array|null
     */
    public function close(int $orderId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders/$orderId/close.json", $params));
    }

    /**
     * @param int $orderId
     * @param array $params
     * @return object|array|null
     */
    public function open(int $orderId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders/$orderId/open.json", $params));
    }

    /**
     * @param int $orderId
     * @param array $params
     * @return object|array|null
     */
    public function cancel(int $orderId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("orders/$orderId/cancel.json", $params));
    }

    /**
     * Updates an article
     * @param int $orderId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $orderId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("orders/$orderId.json", $params));
    }


    /**
     * Deletes an article
     * @param int $orderId
     * @return object|array|null
     */
    public function delete(int $orderId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("orders/$orderId.json")
        );
    }

}
