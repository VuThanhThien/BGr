<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Customers;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class Customer extends BaseResource {

    /**
     * Retrieve all collections
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('customers.json', $params));
    }

    /**
     * Searches for customers that match a supplied query.
     * @param array $params
     * @return object|array|null
     */
    public function search(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('customers/search.json', $params));
    }

    /**
     * Retrieves a count of all customers.
     * @param array $params
     * @return object
     */
    public function count(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('customers/count.json', $params));
    }

    /**
     * Retrieve a single customer by their ID
     * @param int $customerId
     * @return object|array|null
     */
    public function find(int $customerId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("customers/$customerId.json"));
    }

    /**
     * Retrieve all orders from a customer
     * @param int $customerId
     * @param array $params
     * @return object|array|null
     */
    public function orders(int $customerId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("customers/$customerId/orders.json", $params));
    }

    /**
     * Creates a customer.
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post('customers.json', [
            'customer' => $params
        ]));
    }

    /**
     * Generate an account activation URL for a customer whose account is not yet enabled.
     * This is useful when you've imported a large number of customers and want to send them activation emails all at once.
     * Using this approach, you'll need to generate and send the activation emails yourself.
     * The account activation URL generated by this endpoint is for one-time use and will expire after 30 days.
     * If you make a new POST request to this endpoint, then a new URL will be generated.
     * The new URL will be again valid for 30 days, but the previous URL will no longer be valid.
     * @param int $customerId
     * @return object|array|null
     */
    public function createAccountActivation(int $customerId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("customers/$customerId/account_activation_url.json"));
    }

    /**
     * Sends an account invite to a customer.
     * @param int $customerId
     * @param array $params
     * @return object|array|null
     */
    public function createAccountInvite(int $customerId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("customers/$customerId/send_invite.json", $params));
    }

    /**
     * Updates a customer.
     * @param int $customerId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $customerId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("customers/$customerId.json", $params));
    }

    /**
     * Deletes a custom collection
     * @param int $customerId
     * @return object|array|null
     */
    public function delete(int $customerId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("customers/$customerId.json")
        );
    }

}