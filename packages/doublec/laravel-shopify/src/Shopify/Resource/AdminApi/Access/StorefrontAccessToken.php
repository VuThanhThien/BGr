<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Access;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class StorefrontAccessToken extends BaseResource {

    /**
     * @return object|array|null
     */
    public function all(): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('storefront_access_tokens.json'));
    }

    /**
     * Create a new storefront access token
     * @param array $params
     * @return object|array|null
     */
    public function create(array $params): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->post('storefront_access_tokens.json', $params)
        );
    }

    /**
     * Delete an existing storefront access token
     * @param int $accessId
     * @return object|array|null
     */
    public function delete(int $accessId): object|array|null
    {
        return $this->prepareResponse(
            $this->shopifyApi->delete("storefront_access_tokens/{$accessId}.json")
        );
    }

}
