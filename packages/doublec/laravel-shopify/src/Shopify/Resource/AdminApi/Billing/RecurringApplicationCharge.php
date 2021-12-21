<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Billing;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class RecurringApplicationCharge extends BaseResource {

    /**
     * Retrieve all variants for a product
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("recurring_application_charges.json", $params));
    }

    /**
     * Retrieves a single charge
     * @param int $chargeId
     * @return object|array|null
     */
    public function find(int $chargeId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("recurring_application_charges/$chargeId.json"));
    }

    /**
     * Creates a new product variant
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("recurring_application_charges.json", $params));
    }

    /**
     * Updates the capped amount of an active recurring application charge
     * @param int $chargeId
     * @param string $params
     * @return object|array|null
     */
    public function update(int $chargeId, string $params): object|array|null
    {
        $endpoint = "recurring_application_charges/$chargeId/customize.json?" . http_build_query($params);

        return $this->prepareResponse($this->shopifyApi->put($endpoint));
    }

    /**
     * Cancels a recurring application charge
     * @param int $chargeId
     * @return object|array|null
     */
    public function delete(int $chargeId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("recurring_application_charges/$chargeId.json")
        );
    }

}
