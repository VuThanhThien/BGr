<?php

namespace DoubleC\LaravelShopify\Services\AuthenticateApi;

use DoubleC\LaravelShopify\Http\Exceptions\VerifyRequestException;
use DoubleC\LaravelShopify\HttpClient\HttpClientApi;
use DoubleC\LaravelShopify\Shopify\Authentication\Authentication;
use DoubleC\LaravelShopify\Shopify\Authentication\AuthenticationImp;
use Illuminate\Http\RedirectResponse;

class AuthenticateImp implements AuthenticateApi
{

    /**
     * @var Authentication
     */
    private Authentication $authenticate;

    public function __construct()
    {
        $httpClient = new HttpClientApi();
        $httpClient->setVerifyPeer(false);
        $authenticate = new AuthenticationImp($httpClient);
        $authenticate->usingClientId(config('shopify.api_key'))
            ->usingClientSecret(config('shopify.shared_secret'))
            ->withScope(config('shopify.permissions'))
            ->redirectTo(config('shopify.redirect_url'));
        $this->authenticate = $authenticate;

    }

    public function forShopName(string $shopName): AuthenticateApi
    {
        $this->authenticate->forShopName($shopName);
        return $this;
    }

    public function initiateLogin(string $state): RedirectResponse
    {
        return $this->authenticate->initiateLogin($state);
    }

    public function accessToken(string $code): string
    {
        return $this->authenticate->toPermanentToken($code);
    }

    public function isValidShopifyRequest(array $params): bool
    {
        $timestamp = isset($params['timestamp']) ? $params['timestamp'] : null;
        if (!$timestamp || !is_numeric($timestamp)) return false;
        if ($timestamp < (time() - (24 * 60 * 60))) return false;
        return $this->verifySignature($params);
    }

    public function verifySignature(array $params): bool
    {
        $hmac = isset($params['hmac']) ? $params['hmac'] : null;
        if (!$hmac) return false;
        return $this->generateSignature($params) === $hmac;
    }

    public function generateSignature(array $params, string $separator = "&"): string
    {
        $params = collect($params);
        $params->forget(['signature', 'hmac']);
        $params = $params->map(fn($value, $key) => "{$key}={$value}")->toArray();
        sort($params);
        $listed = implode($separator, $params);
        return hash_hmac('sha256', $listed, config('shopify.shared_secret'));
    }
}
