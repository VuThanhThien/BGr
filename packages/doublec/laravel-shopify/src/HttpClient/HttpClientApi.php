<?php

namespace DoubleC\LaravelShopify\HttpClient;

use CurlHandle;
use RuntimeException;

class HttpClientApi implements HttpClient
{

    protected const SHOPIFY_ACCESS_TOKEN_HEADER = 'X-Shopify-Access-Token';

    /**
     * Require verification of SSL certificate used.
     * @var bool
     */
    protected bool $verifyPeer = true;

    /**
     * Set to 1 to check the existence of a common name in the SSL peer
     * certificate
     * Set to 2 to check the existence of a common name and also verify
     * that it matches the hostname provided.
     * In production environments the value of this option should
     * be kept at 2 (default value).
     * @var int
     */
    protected int $verifyHost = 2;

    /**
     * The name of a file holding one or more certificates to verify
     * the peer with. This only makes sense when used in combination
     * with CURLOPT_SSL_VERIFYPEER
     * @var string
     */
    protected string $certificatePath;

    /**
     * Header request
     * @var array
     */
    protected array $headers;

    /**
     * @var string
     */
    protected string $token = '';

    /**
     * HttpClientApi constructor.
     * @param string $certificatePath
     */
    public function __construct(string $certificatePath = '')
    {
        $this->certificatePath = $certificatePath;
        $this->headers = [];
    }

    /**
     * @inheritDoc
     */
    public function setAccessToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @inheritDoc
     */
    public function getAccessToken(): string
    {
        return $this->token;
    }

    /**
     * @param bool $verifyPeer
     */
    public function setVerifyPeer(bool $verifyPeer): void
    {
        $this->verifyPeer = $verifyPeer;
    }

    public function setVerifyHost(int $verifyHost): void
    {
        $this->verifyHost = $verifyHost;
    }

    /**
     * @param $ch
     * @return bool|string
     */
    public function handleRequest($ch): bool|string
    {
        if (count($this->headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // Get http code from response
        $responseHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $retry = 0;

        // Retry 6 times for preventing call limit API
        while (($responseHttpCode === CURLE_COULDNT_RESOLVE_HOST || $responseHttpCode === 429) && $retry < 6) {
            sleep(1 + $retry);
            $response = curl_exec($ch);
            $error = curl_error($ch);
            $responseHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $retry++;
        }

        if ($error) {
            curl_close($ch);
            throw new RuntimeException($error, $responseHttpCode);
        }

        curl_close($ch);
        return $response;
    }

    /**
     * @param string $uri
     * @return CurlHandle|bool
     */
    public function initCurl(string $uri): CurlHandle|bool
    {
        $this->headers = array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_USERAGENT, 'doublec/shopify-http-client');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->verifyHost);

        if ($this->getAccessToken()) {
            $this->headers[] = self::SHOPIFY_ACCESS_TOKEN_HEADER
                . ": " . $this->getAccessToken();
        }

        if (!$this->verifyPeer) {

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        } else {

            if (!file_exists($this->certificatePath)) {
                throw new RuntimeException('cacert.pem file not found');
            }

            curl_setopt($ch, CURLOPT_CAINFO, $this->certificatePath);

        }
        return $ch;
    }

    /**
     * @inheritDoc
     */
    public function get(string $uri, array $params = []): bool|string
    {
        $uri .= '?' . http_build_query($params);

        $ch = $this->initCurl($uri);

        return $this->handleRequest($ch);
    }

    /**
     * @inheritDoc
     */
    public function post(string $uri, array $params = []): bool|string
    {
        $ch = $this->initCurl($uri);

        /** @var resource $ch */
        curl_setopt($ch, CURLOPT_POST, true);

        if ($params) {
            $this->headers[] = 'Content-Type: application/json';
        }

        if (!is_null($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        return $this->handleRequest($ch);
    }

    /**
     * @inheritDoc
     */
    public function put(string $uri, array $params = []): bool|string
    {
        $ch = $this->initCurl($uri);

        /** @var resource $ch */
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        if ($params) {
            $this->headers[] = 'Content-Type: application/json';
        }

        if (!is_null($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        return $this->handleRequest($ch);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uri, array $params = []): bool|string
    {
        $ch = $this->initCurl($uri);

        /** @var resource $ch */
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        if ($params) {
            $this->headers[] = 'Content-Type: application/json';
        }

        if (!is_null($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        return $this->handleRequest($ch);
    }
}
