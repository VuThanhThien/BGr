<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Products;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class ProductVariant extends BaseResource {

    /**
     * Retrieve all variants for a product
     * @param int $productId
     * @param array $params
     * @return object|array|null
     */
    public function all(int $productId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("products/$productId/variants.json", $params));
    }

    /**
     * Retrieves a count of product variants
     * @param int $productId
     * @param array $params
     * @return object|array|null
     */
    public function count(int $productId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("products/$productId/variants/count.json", $params));
    }

    /**
     * Retrieves a single product variant by ID
     * @param int $variantId
     * @return object|array|null
     */
    public function find(int $variantId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("variants/variants/$variantId.json"));
    }

    /**
     * Creates a new product variant
     * @param int $productId
     * @param array $params
     * @return object|array|null
     */
    public function create(int $productId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("products/$productId/variants.json", $params));
    }

    /**
     * Updates an existing product variant
     * @param int $variantId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $variantId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("variants/$variantId.json", $params));
    }

    /**
     * Delete a product variant
     * @param int $productId
     * @param int $variantId
     * @return object|array|null
     */
    public function delete(int $productId, int $variantId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("products/$productId/variants/$variantId.json")
        );
    }

}
