<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Metafield;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class Metafield extends BaseResource {

    /**
     * Retrieves a list of metafields that belong to a resource
     * @param string $resource
     * @param int $resourceId
     * @param array $params
     * @return object|array|null
     */
    public function all(string $resource = '', int $resourceId = 0, array $params = []): object|array|null
    {
        $endpoint = $resource && $resourceId ? "$resource/$resourceId/metafields.json" : "metafields.json";

        return $this->prepareResponse($this->shopifyApi->get($endpoint, $params));
    }

    /**
     * Retrieves a count of a resource's metafields.
     * @param string $resource
     * @param int $resourceId
     * @param array $params
     * @return object|array|null
     */
    public function count(string $resource = '', int $resourceId = 0, array $params = []): object|array|null
    {
        $endpoint = $resource && $resourceId ? "$resource/$resourceId/metafields/count.json" : "metafields/count.json";

        return $this->prepareResponse($this->shopifyApi->get($endpoint, $params));
    }

    /**
     * Retrieves a count of a resource's metafields.
     * @param int $metafieldId
     * @param string $resource
     * @param int $resourceId
     * @param array $params
     * @return object|array|null
     */
    public function find(int $metafieldId, string $resource = '', int $resourceId = 0, array $params = []): object|array|null
    {
        $endpoint = $resource && $resourceId ? "$resource/$resourceId/metafields/$metafieldId.json" : "metafields/$metafieldId.json";

        return $this->prepareResponse($this->shopifyApi->get($endpoint, $params));
    }

    /**
     * Creates a new metafield for a resource.
     * @param array $params
     * @param string $resource
     * @param int $resourceId
     * @return object|array|null
     */
    public function create(array $params, string $resource = '', int $resourceId = 0, ): object|array|null
    {

        $endpoint = $resource && $resourceId ? "$resource/$resourceId/metafields.json" : "metafields.json";

        return $this->prepareResponse($this->shopifyApi->post($endpoint, $params));
    }

    /**
     * Updates the capped amount of an active recurring application charge
     * @param int $metafieldId
     * @param array $params
     * @param string $resource
     * @param int $resourceId
     * @return object|array|null
     */
    public function update(int $metafieldId, array $params, string $resource = '', int $resourceId = 0): object|array|null
    {
        $endpoint = $resource && $resourceId ? "$resource/$resourceId/metafields/$metafieldId.json" : "metafields/$metafieldId.json";

        return $this->prepareResponse($this->shopifyApi->put($endpoint, $params));
    }

    /**
     * Cancels a recurring application charge
     * @param int $metafieldId
     * @param string $resource
     * @param int $resourceId
     * @return object|array|null
     */
    public function delete(int $metafieldId, string $resource = '', int $resourceId = 0): object|array|null
    {
        $endpoint = $resource && $resourceId ? "$resource/$resourceId/metafields/$metafieldId.json" : "metafields/$metafieldId.json";

        return $this->prepareResponse(
            $this->shopifyApi->delete($endpoint)
        );
    }

}
