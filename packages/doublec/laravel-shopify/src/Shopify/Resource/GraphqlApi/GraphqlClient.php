<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\GraphqlApi;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use DoubleC\LaravelShopify\Http\Exceptions\ShopifyGraphqlException;

class GraphqlClient
{

    /** @var string */
    const HEADER_NAME = 'X-Shopify-Access-Token';

    /** @var int */
    const HTTP_OK = 200;

    /** @var string */
    protected $shopName;

    /**
     * @see https://help.shopify.com/en/api/graphql-admin-api/getting-started#the-graphql-endpoint
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'application/graphql'
    ];

    /** @var Client */
    private $client;

    /**
     * GraphqlClient constructor.
     *
     * @param string $shopName
     * @param string $accessToken
     */
    public function __construct(string $shopName = null, string $accessToken = null)
    {
        $this->shopName = $shopName;
        if (!is_null($accessToken)) {
            $this->headers[self::HEADER_NAME] = $accessToken;
        }
        $this->client = new Client(['idn_conversion' => false, 'verify' => false]);
    }

    /**
     * Method POST uses to request to shopify graphql
     *
     * @param string $params
     * @return \stdClass
     * @throws ShopifyGraphqlException
     */
    public function post(string $params): \stdClass
    {
        $request = new Request('POST', $this->buildUri(), $this->headers, $params);
        try {
            $response = $this->client->send($request);
            $result = json_decode($response->getBody()->getContents());
            if ($response->getStatusCode() != self::HTTP_OK || isset($result->errors)) {
                throw new ShopifyGraphqlException(json_encode($result->errors), $response->getStatusCode());
            }
            return $result;
        } catch (GuzzleException $e) {
            throw new ShopifyGraphqlException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }


    /**
     * Build the endpoint for request
     *
     * @return string
     */
    protected function buildUri(): string
    {
        return "https://{$this->shopName}.myshopify.com/admin/api/graphql.json";
    }

    /**
     * Set shop name of client for requesting to shopify grahpql admin
     *
     * @param string $shopName
     * @return $this
     */
    public function setShopName(string $shopName): GraphqlClient
    {
        $this->shopName = $shopName;
        return $this;
    }

    /**
     * Set access token for client
     *
     * @param string $accessToken
     * @return GraphqlClient
     */
    public function setAccessToken(string $accessToken): GraphqlClient
    {
        $this->headers[self::HEADER_NAME] = $accessToken;
        return $this;
    }
}