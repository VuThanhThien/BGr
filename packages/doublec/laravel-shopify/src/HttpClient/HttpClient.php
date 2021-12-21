<?php

namespace DoubleC\LaravelShopify\HttpClient;

use CurlHandle;

interface HttpClient
{
    /**
     * @param string $token
     */
    public function setAccessToken(string $token): void;

    /**
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * @param string $uri
     * @return CurlHandle|false
     */

    /**
     * @param string $uri
     * @param array $params
     * @return bool|string
     */
    public function get(string $uri, array $params = []): bool|string;

    /**
     * @param string $uri
     * @param array $params
     * @return bool|string
     */
    public function post(string $uri, array $params = []): bool|string;

    /**
     * @param string $uri
     * @param array $params
     * @return bool|string
     */
    public function put(string $uri, array $params = []): bool|string;

    /**
     * @param string $uri
     * @param array $params
     * @return bool|string
     */
    public function delete(string $uri, array $params = []): bool|string;
}
