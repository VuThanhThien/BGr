<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Inventory;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class InventoryLevel extends BaseResource {

    /**
     * Retrieves a list of inventory levels.
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("inventory_levels.json", $params));
    }

    /**
     * Adjusts the inventory level of an inventory item at a single location
     * @param array $params
     * @return object|array|null
     */
    public function adjust(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("inventory_levels/adjust.json", $params));
    }

    /**
     * Connects an inventory item to a location by creating an inventory level at that location.
     * @param array $params
     * @return object|array|null
     */
    public function connect(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("inventory_levels/connect.json", $params));
    }

    /**
     * Sets the inventory level for an inventory item at a location
     * @param array $params
     * @return object|array|null
     */
    public function set(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("inventory_levels/set.json", $params));
    }

    /**
     * Deletes an inventory level of an inventory item at a location
     * @param array $params
     * @return object|array|null
     */
    public function delete(array $params = []): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("inventory_levels.json", $params)
        );
    }

}
