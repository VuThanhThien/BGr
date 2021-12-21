<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Theme extends BaseResource
{

    /**
     * Retrieve all collects for the shop
     * Retrieve only collects for a certain product
     * Retrieve only collects for a certain collection
     * @param array $params
     * @return array
     */
    public function all(array $params = []): array
    {
        return $this->prepareResponse($this->shopifyApi->get("themes.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $themeId
     * @return object|array|null
     */
    public function find(int $themeId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("themes/$themeId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("themes.json", $params));
    }

    /**
     * Updates an article
     * @param int $themeId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $themeId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("themes/$themeId.json", $params));
    }

    /**
     * Deletes an article
     * @param int $themeId
     * @return object|array|null
     */
    public function delete(int $themeId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("themes/$themeId.json")
        );
    }

}
