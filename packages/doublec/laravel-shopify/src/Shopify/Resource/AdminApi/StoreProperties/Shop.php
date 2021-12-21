<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\StoreProperties;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Shop extends BaseResource
{

    /**
     * Retrieves the shop's configuration
     * @return object|array|null
     */
    public function all(): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get('shop.json'));
    }

}
