<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Access;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;

class AccessScope extends BaseResource
{
    /**
     * Retrieves a list of access scopes associated to the access token.
     * @return object|array|null
     */
    public function all(): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('oauth/access_scopes.json'));
    }
}
