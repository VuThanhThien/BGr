<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Products;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class Product extends BaseResource {

    /**
     * Retrieve all products
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('products.json', $params));
    }

    /**
     * Retrieves a count of products.
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('products/count.json', $params));
    }

    /**
     * Retrieves a single product.
     * @param int $productId
     * @return object|array|null
     */
    public function find(int $productId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("products/$productId.json"));
    }

    /**
     * Creates a new product
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post('products.json', $params));
    }

    /**
     * Updates a product and its variants and images.
     * @param int $productId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $productId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("products/$productId.json", $params));
    }

    /**
     * Deletes a product.
     * @param int $productId
     * @return object|array|null
     */
    public function delete(int $productId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("products/$productId.json")
        );
    }

}
