<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Inventory;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class InventoryItem extends BaseResource
{

    /**
     * Retrieves a list of inventory items
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('inventory_items.json', $params));
    }

    /**
     * Retrieves a single inventory item by ID
     * @param int $inventoryId
     * @return object|array|null
     */
    public function find(int $inventoryId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("inventory_items/$inventoryId.json"));
    }

    /**
     * Updates an existing inventory item
     * @param int $inventoryId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $inventoryId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("inventory_items/$inventoryId.json", $params));
    }

}
