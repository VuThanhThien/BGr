<?php

namespace DoubleC\LaravelShopify\Services\AuthenticateApi;

use Illuminate\Http\RedirectResponse;

interface AuthenticateApi
{
    /**
     * @param string $shopName
     * @return AuthenticateApi
     */
    public function forShopName(string $shopName): AuthenticateApi;

    /**
     * @param string $state
     * @return RedirectResponse
     */
    public function initiateLogin(string $state): RedirectResponse;

    /**
     * @param string $code
     * @return string
     */
    public function accessToken(string $code): string;

    /**
     * Verify shopify request
     * @param array $params
     * @return bool
     */
    public function isValidShopifyRequest(array $params): bool;

    /**
     * Verify request with params
     * @param array $params
     * @return bool
     */
    public function verifySignature(array $params): bool;

    /**
     * Generate signature with params
     * @param array $params
     * @param string $separator
     * @return string
     */
    public function generateSignature(array $params, string $separator = "&"): string;
}

