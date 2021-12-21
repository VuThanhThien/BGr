<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Redirect extends BaseResource
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
        return $this->prepareResponse($this->shopifyApi->get("redirects.json", $params));
    }

    /**
     * Get a count of all blogs
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("redirects/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $redirectId
     * @return object|array|null
     */
    public function find(int $redirectId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("redirects/$redirectId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("redirects.json", $params));
    }

    /**
     * Updates an article
     * @param int $redirectId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $redirectId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("redirects/$redirectId.json", $params));
    }

    /**
     * Deletes an article
     * @param int $redirectId
     * @return object|array|null
     */
    public function delete(int $redirectId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("redirects/$redirectId.json")
        );
    }

}
