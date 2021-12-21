<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Products;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class ProductImage extends BaseResource {

    /**
     * Get all product images
     * @param int $productId
     * @param array $params
     * @return object|array|null
     */
    public function all(int $productId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("products/$productId/images.json", $params));
    }

    /**
     * Get a count of all product images
     * @param int $productId
     * @param array $params
     * @return object|array|null
     */
    public function count(int $productId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("products/$productId/images/count.json", $params));
    }

    /**
     * Get all product images
     * @param int $productId
     * @param int $imageId
     * @return object|array|null
     */
    public function find(int $productId, int $imageId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("products/$productId/images/$imageId.json"));
    }

    /**
     * Creates a new product image
     * @param int $productId
     * @param array $params
     * @return object|array|null
     */
    public function create(int $productId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("products/$productId/images.json", $params));
    }

    /**
     * Updates a product and its variants and images.
     * @param int $productId
     * @param int $imageId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $productId, int $imageId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("products/$productId/images/$imageId.json", $params));
    }

    /**
     * Deletes a product image
     * @param int $productId
     * @param int $imageId
     * @return object|array|null
     */
    public function delete(int $productId, int $imageId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("products/$productId/images/$imageId.json")
        );
    }

}
