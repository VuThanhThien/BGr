<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Article extends BaseResource
{

    /**
     * Retrieve all collects for the shop
     * Retrieve only collects for a certain product
     * Retrieve only collects for a certain collection
     * @param int $blogId
     * @param array $params
     * @return object|array|null
     */
    public function all(int $blogId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("blogs/$blogId/articles.json", $params));
    }

    /**
     * Retrieves a count of all articles from a blog
     * @param int $blogId
     * @param array $params
     * @return object|array|null
     */
    public function count(int $blogId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("blogs/$blogId/articles/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $blogId
     * @param int $articleId
     * @return object|array|null
     */
    public function find(int $blogId, int $articleId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("blogs/$blogId/articles/$articleId.json"));
    }

    /**
     * Retrieves a list all of article authors
     * @return object|array|null
     */
    public function authors(): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("articles/authors.json"));
    }

    /**
     * Retrieves a list of all the tags
     * @param array $params
     * @param int $blogId
     * @return object|array|null
     */
    public function tags(array $params = [], int $blogId = 0): object|array|null
    {
        $endpoint = $blogId ? "blogs/$blogId/articles/tags.json" : "articles/tags.json";

        return $this->prepareResponse($this->shopifyApi->get($endpoint, $params));
    }

    /**
     * Creates an article for a blog
     * @param int $blogId
     * @param array $params
     * @return object|array|null
     */
    public function create(int $blogId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("blogs/$blogId/articles.json", $params));
    }

    /**
     * Updates an article
     * @param int $blogId
     * @param int $articleId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $blogId, int $articleId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("blogs/$blogId/articles/$articleId.json", $params));
    }

    /**
     * Deletes an article
     * @param int $blogId
     * @param int $articleId
     * @return object|array|null
     */
    public function delete(int $blogId, int $articleId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("blogs/$blogId/articles/$articleId.json")
        );
    }

}
