<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class PriceRule extends BaseResource
{

    /**
     * Retrieve all collects for the shop
     * Retrieve only collects for a certain product
     * Retrieve only collects for a certain collection
     * @param array $params
     * @return object|array|null
     */
    public function all(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("price_rules.json", $params));
    }

    /**
     * @param array $params
     * @return object|array|null
     */
    public function count(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("price_rules/count.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $priceRuleId
     * @return object|array|null
     */
    public function find(int $priceRuleId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("price_rules/$priceRuleId.json"));
    }

    /**
     * Creates an article for a blog
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("price_rules.json", $params));
    }

    /**
     * Updates an article
     * @param int $priceRuleId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $priceRuleId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("price_rules/$priceRuleId.json", $params));
    }


    /**
     * Deletes an article
     * @param int $priceRuleId
     * @return object|array|null
     */
    public function delete(int $priceRuleId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("price_rules/$priceRuleId.json")
        );
    }

}
