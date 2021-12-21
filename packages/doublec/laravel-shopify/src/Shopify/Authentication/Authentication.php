<?php

namespace DoubleC\LaravelShopify\Shopify\Authentication;

use Illuminate\Http\RedirectResponse;

interface Authentication {

    /**
     * @param string $shopName
     * @return Authentication
     */
    public function forShopName(string $shopName): Authentication;

    /**
     * @param string $clientId
     * @return Authentication
     */
    public function usingClientId(string $clientId): Authentication;

    /**
     * @param array $scope
     * @return Authentication
     */
    public function withScope(array $scope): Authentication;

    /**
     * @param $uri
     * @return Authentication
     */
    public function redirectTo(string $uri): Authentication;

    /**
     * @param string $state
     * @return mixed
     */
    public function initiateLogin(string $state): RedirectResponse;

    /**
     * @param string $clientSecret
     * @return Authentication
     */
    public function usingClientSecret(string $clientSecret): Authentication;

    /**
     * In your request, {shop} is the name of the user’s shop and the following parameters must be provided in the request body:
     * client_id: The API key for the app, as defined in the Partner Dashboard.
     * client_secret: The API secret key for the app, as defined in the Partner Dashboard.
     * code: The authorization code provided in the redirect.
     * @param string $temporaryToken
     * @return string
     */
    public function toPermanentToken(string $temporaryToken): string;

}
