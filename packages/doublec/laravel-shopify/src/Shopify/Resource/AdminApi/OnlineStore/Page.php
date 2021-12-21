<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Page extends BaseResource
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
        return $this->prepareResponse($this->shopifyApi->get("pages.json", $params));
    }

    /**
     * Get a count of all blogs
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("pages/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $pageId
     * @return object|array|null
     */
    public function find(int $pageId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("pages/$pageId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("pages.json", $params));
    }

    /**
     * Updates an article
     * @param int $pageId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $pageId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("pages/$pageId.json", $params));
    }

    /**
     * Deletes an article
     * @param int $pageId
     * @return object|array|null
     */
    public function delete(int $pageId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("pages/$pageId.json")
        );
    }

}
