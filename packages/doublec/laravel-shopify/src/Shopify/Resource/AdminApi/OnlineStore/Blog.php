<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Blog extends BaseResource
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
        return $this->prepareResponse($this->shopifyApi->get("blogs.json", $params));
    }

    /**
     * Get a count of all blogs
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("blogs/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $blogId
     * @return object|array|null
     */
    public function find(int $blogId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("blogs/$blogId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("blogs.json", $params));
    }

    /**
     * Updates an article
     * @param int $blogId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $blogId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("blogs/$blogId.json", $params));
    }

    /**
     * Deletes an article
     * @param int $blogId
     * @return object|array|null
     */
    public function delete(int $blogId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("blogs/$blogId.json")
        );
    }

}
