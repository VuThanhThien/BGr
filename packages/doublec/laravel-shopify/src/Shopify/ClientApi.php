<?php

namespace DoubleC\LaravelShopify\Shopify;

use DoubleC\LaravelShopify\Shopify\Request\Client;
use http\Exception\RuntimeException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ClientApi
{

    /**
     * @var Client
     */
    private Client $client;

    /**
     * ClientApi constructor.
     * @param string $shopName
     * @param string $apiVersion
     * @param string $token
     */
    public function __construct(string $shopName = '', string $apiVersion = '', string $token = '')
    {
        $client = new Client();

        $client->setVerifyPeer(false);

        if ($shopName) {
            $client->setShopName($shopName);
        }

        if ($apiVersion) {
            $client->setApiVersion($apiVersion);
        } else {
            $client->setApiVersion(config('shopify.api_version'));
        }

        if ($token) {
            $client->setAccessToken($token);
        }

        $this->client = $client;

    }

    /**
     * @param string $shopName
     */
    public function setShopName(string $shopName) {
        $this->client->setShopName($shopName);
    }

    /**
     * @param string $apiVersion
     */
    public function setApiVersion(string $apiVersion) {
        $this->client->setApiVersion($apiVersion);
    }

    /**
     * @param string $token
     */
    public function setToken(string $token) {
        $this->client->setAccessToken($token);
    }

    /**
     * @param string $uri
     * @param array $params
     * @return bool|string
     */
    public function get(string $uri, array $params = []): bool|string
    {
        if (!$this->client->canRequest()) {
            throw new RuntimeException(
                'Shopify request missing parameter'
            );
        }
        $response = $this->client->get($this->client->getEndPointUri($uri), $params);

        if (isset($response->errors)) {
            throw new BadRequestHttpException(json_encode($response->errors));
        }

        return $response;

    }

    /**
     * @param string $uri
     * @param array $params
     * @return bool|string
     */
    public function post(string $uri, array $params = []): bool|string
    {
        if (!$this->client->canRequest()) {
            throw new RuntimeException(
                'Shopify request missing parameter'
            );
        }
        $response = $this->client->post($this->client->getEndPointUri($uri), $params);

        if (isset($response->errors)) {
            throw new BadRequestHttpException(json_encode($response->errors));
        }

        return $response;
    }

    /**
     * @param string $uri
     * @param array $params
     * @return bool|string
     */
    public function put(string $uri, array $params = []): bool|string
    {
        if (!$this->client->canRequest()) {
            throw new RuntimeException(
                'Shopify request missing parameter'
            );
        }
        $response = $this->client->put($this->client->getEndPointUri($uri), $params);

        if (isset($response->errors)) {
            throw new BadRequestHttpException(json_encode($response->errors));
        }

        return $response;
    }

    /**
     * @param string $uri
     * @param array $params
     * @return bool|string
     */
    public function delete(string $uri, array $params = []): bool|string
    {
        if (!$this->client->canRequest()) {
            throw new RuntimeException(
                'Shopify request missing parameter'
            );
        }
        $response = $this->client->delete($this->client->getEndPointUri($uri), $params);

        if (isset($response->errors)) {
            throw new BadRequestHttpException(json_encode($response->errors));
        }

        return $response;
    }

}
