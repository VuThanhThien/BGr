<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Discounts;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class DiscountCode extends BaseResource
{

    /**
     * Retrieve all collects for the shop
     * Retrieve only collects for a certain product
     * Retrieve only collects for a certain collection
     * @param int $priceRulId
     * @param array $params
     * @return object|array|null
     */
    public function all(int $priceRulId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("price_rules/$priceRulId/discount_codes.json", $params));
    }

    /**
     * Get a count of all blogs
     * @param array $params
     * @return object|array|null
     */
    public function search(array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("discount_codes/lookup.json", $params));
    }

    /**
     * Retrieves a single article
     * @param int $priceRuleId
     * @param int $discountCodeId
     * @return object|array|null
     */
    public function find(int $priceRuleId, int $discountCodeId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("price_rules/$priceRuleId/discount_codes/$discountCodeId.json"));
    }

    /**
     * @param int $priceRuleId
     * @param int $batchId
     * @return object|array|null
     */
    public function findCreationJob(int $priceRuleId, int $batchId): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("price_rules/$priceRuleId/batch/$batchId.json"));
    }

    /**
     * @param int $priceRulId
     * @param int $batchId
     * @param array $params
     * @return object|array|null
     */
    public function allCreationJob(int $priceRulId, int $batchId, array $params = []): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("price_rules/$priceRulId/batch/$batchId/discount_codes.json", $params));
    }

    /**
     * Creates an article for a blog
     * @param int $priceRuleId
     * @param array $params
     * @return object|array|null
     */
    public function create(int $priceRuleId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("price_rules/$priceRuleId/discount_codes.json", $params));
    }

    public function createMany(int $priceRuleId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->post("price_rules/$priceRuleId/batch.json", $params));
    }

    /**
     * Updates an article
     * @param int $priceRuleId
     * @param int $discountCodeId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $priceRuleId, int $discountCodeId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("price_rules/$priceRuleId/discount_codes/$discountCodeId.json", $params));
    }


    /**
     * Deletes an article
     * @param int $priceRuleId
     * @param int $discountCodeId
     * @return object|array|null
     */
    public function delete(int $priceRuleId, int $discountCodeId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("price_rules/$priceRuleId/discount_codes/$discountCodeId.json")
        );
    }

}
