<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Inventory;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class Location extends BaseResource {

    /**
     * Retrieves a list of locations
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("locations.json", $params));
    }

    /**
     * Retrieves a single location by its ID
     * @param int $locationId
     * @return object|array|null
     */
    public function find(int $locationId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("locations/$locationId.json"));
    }

    /**
     * Retrieves a count of locations
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('locations/count.json', $params));
    }

    /**
     * Retrieves a single location by its ID
     * @param int $locationId
     * @return object|array|null
     */
    public function inventoryLevel(int $locationId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("locations/$locationId/inventory_levels.json"));
    }

}
