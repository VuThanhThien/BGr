<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Comment extends BaseResource
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
        return $this->prepareResponse($this->shopifyApi->get("comments.json", $params));
    }

    /**
     * Get a count of all blogs
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("comments/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $commentId
     * @return object|array|null
     */
    public function find(int $commentId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("comments/$commentId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("comments.json", $params));
    }

    /**
     * Creates an article for a blog
     * @param int $commentId
     * @param array $params
     * @return object|array|null
     */
    public function spam(int $commentId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("comments/$commentId/spam.json", $params));
    }

    /**
     * Creates an article for a blog
     * @param int $commentId
     * @param array $params
     * @return object|array|null
     */
    public function notSpam(int $commentId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("comments/$commentId/not_spam.json", $params));
    }

    /**
     * Creates an article for a blog
     * @param int $commentId
     * @param array $params
     * @return object|array|null
     */
    public function approve(int $commentId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("comments/$commentId/approve.json", $params));
    }

    /**
     * Deletes an article
     * @param int $commentId
     * @param array $params
     * @return object|array|null
     */
    public function delete(int $commentId, array $params): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->post("comments/$commentId/remove.json", $params)
        );
    }

    /**
     * Creates an article for a blog
     * @param int $commentId
     * @param array $params
     * @return object|array|null
     */
    public function restore(int $commentId, array $params): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->post("comments/$commentId/restore.json", $params)
        );
    }

}
