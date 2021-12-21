<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\SalesChannel;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class CollectionListing extends BaseResource
{

    /**
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('collection_listings.json', $params));
    }

    /**
     * @param int $collectionId
     * @return object|array|null
     */
    public function productIds(int $collectionId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("collection_listings/$collectionId/product_ids.json"));
    }

    /**
     * Retrieve a collect with a certain ID
     * @param int $collectionId
     * @return object|array|null
     */
    public function find(int $collectionId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("collection_listings/$collectionId.json"));
    }

    /**
     * Create a new link between an existing product and an existing collection
     * @param int $collectionId
     * @param array $params
     * @return object|array|null
     */
    public function create(int $collectionId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("collection_listings/$collectionId.json", $params));
    }

    /**
     * Delete the link between a product an a collection
     * @param int $collectionId
     * @return object|array|null
     */
    public function delete(int $collectionId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("collection_listings/$collectionId.json")
        );
    }

}
