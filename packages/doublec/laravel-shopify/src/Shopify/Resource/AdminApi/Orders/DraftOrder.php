<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Orders;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class DraftOrder extends BaseResource
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
        return $this->prepareResponse($this->shopifyApi->get("draft_orders.json", $params));
    }

    /**
     * Get a count of all blogs
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("draft_orders/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $draftOrderId
     * @return object|array|null
     */
    public function find(int $draftOrderId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("draft_orders/$draftOrderId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("draft_orders.json", $params));
    }

    /**
     * @param int $draftOrderId
     * @param array $params
     * @return object|array|null
     */
    public function sendInvoice(int $draftOrderId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("draft_orders/$draftOrderId/send_invoice.json", $params));
    }

    /**
     * Updates an article
     * @param int $draftOrderId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $draftOrderId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("draft_orders/$draftOrderId.json", $params));
    }

    /**
     * @param int $draftOrderId
     * @param array $params
     * @return object|array|null
     */
    public function complete(int $draftOrderId, array $params = []): object|array|null
    {
        $endpoint = $params ? "draft_orders/$draftOrderId/complete.json?" . http_build_query($params) : "draft_orders/$draftOrderId/complete.json";

        return $this->prepareResponse($this->shopifyApi->put($endpoint));
    }


    /**
     * Deletes an article
     * @param int $draftOrderId
     * @return object|array|null
     */
    public function delete(int $draftOrderId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("draft_orders/$draftOrderId.json")
        );
    }

}
