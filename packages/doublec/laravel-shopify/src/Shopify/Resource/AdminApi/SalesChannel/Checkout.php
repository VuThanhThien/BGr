<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\SalesChannel;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class Checkout extends BaseResource {

    /**
     * Retrieve a specific collection by its ID
     * @param string $token
     * @return object|array|null
     */
    public function find(string $token): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("checkouts/$token.json"));
    }

    /**
     * @param string $token
     * @return object|array|null
     */
    public function shippingRate(string $token): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->get("checkouts/$token/shipping_rates.json")
        );
    }

    /**
     * Create a custom collection
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post('checkouts.json', $params));
    }

    /**
     * @param string $token
     * @param array $params
     * @return object|array|null
     */
    public function complete(string $token, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("checkouts/$token/complete.json", $params));
    }

    /**
     * Updates an existing custom collection
     * @param string $token
     * @param array $params
     * @return object|array|null
     */
    public function update(string $token, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("checkouts/$token.json", [
            'checkout' => $params
        ]));
    }

}
