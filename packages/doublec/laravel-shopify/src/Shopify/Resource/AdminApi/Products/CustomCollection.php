<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Products;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class CustomCollection extends BaseResource {

    /**
     * Retrieve all collections
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('custom_collections.json', $params));
    }

    /**
     * Count all custom collections
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('custom_collections/count.json', $params));
    }

    /**
     * Retrieve a specific collection by its ID
     * @param int $collectionId
     * @return object|array|null
     */
    public function find(int $collectionId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("custom_collections/$collectionId.json"));
    }

    /**
     * Create a custom collection
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post('custom_collections.json', $params));
    }

    /**
     * Updates an existing custom collection
     * @param int $collectionId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $collectionId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("custom_collections/$collectionId.json", $params));
    }

    /**
     * Deletes a custom collection
     * @param int $collectionId
     * @return object|array|null
     */
    public function delete(int $collectionId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("custom_collections/$collectionId.json")
        );
    }

}
