<?php

namespace DoubleC\LaravelShopify\Shopify\Resource;

use DoubleC\LaravelShopify\Shopify\ClientApi;

abstract class BaseResource
{

    /**
     * @var ClientApi
     */
    protected ClientApi $shopifyApi;

    /**
     * BaseResource constructor.
     * @param ClientApi $clientApi
     */
    public function __construct(ClientApi $clientApi)
    {
        $this->shopifyApi = $clientApi;
    }

    /**
     * @param string $response
     * @return object|array|null
     */
    public function prepareResponse(string $response): object|array|null
    {
        return json_decode($response, true);
    }

}
