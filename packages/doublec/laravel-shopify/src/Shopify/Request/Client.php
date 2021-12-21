<?php

namespace DoubleC\LaravelShopify\Shopify\Request;

use DoubleC\LaravelShopify\HttpClient\HttpClientApi;
use JetBrains\PhpStorm\Pure;

class Client extends HttpClientApi
{

    const END_POINT = "https://%s.myshopify.com/admin/api/%s/";

    protected string $apiVersion;

    protected string $shopName;

    public function setShopName(string $shopName)
    {
        $this->shopName = $shopName;
    }

    public function getShopName(): string
    {
        return $this->shopName ?? '';
    }

    public function setApiVersion(string $version)
    {
        $this->apiVersion = $version;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion ?? '';
    }

    #[Pure]
    public function getEndPointUri(string $uri): string
    {
        $endPoint = sprintf(self::END_POINT, $this->getShopName(), $this->getApiVersion());
        $endPoint = "{$endPoint}{$uri}";
        info($endPoint);
        return $endPoint;
    }

    #[Pure]
    public function canRequest(): bool
    {
        if ($this->getShopName() && $this->getApiVersion() && $this->getAccessToken()) {
            return true;
        }
        return false;
    }

}
