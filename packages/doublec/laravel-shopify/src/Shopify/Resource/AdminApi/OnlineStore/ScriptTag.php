<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class ScriptTag extends BaseResource
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
        return $this->prepareResponse($this->shopifyApi->get("script_tags.json", $params));
    }

    /**
     * Get a count of all blogs
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("script_tags/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $scriptTagId
     * @return object|array|null
     */
    public function find(int $scriptTagId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("script_tags/$scriptTagId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("script_tags.json", $params));
    }

    /**
     * Updates an article
     * @param int $scriptTagId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $scriptTagId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("script_tags/$scriptTagId.json", $params));
    }

    /**
     * Deletes an article
     * @param int $scriptTagId
     * @return object|array|null
     */
    public function delete(int $scriptTagId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("script_tags/$scriptTagId.json")
        );
    }

}
