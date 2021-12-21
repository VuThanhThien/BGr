<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Products;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Collect extends BaseResource
{

    /**
     * Retrieve all collects for the shop
     * Retrieve only collects for a certain product
     * Retrieve only collects for a certain collection
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('collects.json', $params));
    }

    /**
     * Create a new link between an existing product and an existing collection
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post('storefront_access_tokens.json', $params));
    }

    /**
     * Delete the link between a product an a collection
     * @param int $collectId
     * @return object|array|null
     */
    public function delete(int $collectId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("collects/$collectId.json")
        );
    }

    /**
     * Count all collects for the shop
     * Count only collects for a certain product
     * Count only collects for a certain collection
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params): object|array|null
    {

        return $this->prepareResponse($this->shopifyApi->get('collects/count.json', $params));
    }

    /**
     * Retrieve a collect with a certain ID
     * @param int $collectId
     * @return object|array|null
     */
    public function find(int $collectId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("collects/$collectId.json"));
    }

}
