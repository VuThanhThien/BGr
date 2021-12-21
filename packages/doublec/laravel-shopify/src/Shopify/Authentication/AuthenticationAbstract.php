<?php

namespace DoubleC\LaravelShopify\Shopify\Authentication;


use DoubleC\LaravelShopify\HttpClient\HttpClient;
use http\Exception\RuntimeException;
use JetBrains\PhpStorm\Pure;

/**
 * Public apps and custom apps must authenticate using the OAuth 2.0 specification in order to use Shopify’s API resources.
 * Class AuthenticationAbstract
 * @package DoubleC\LaravelShopify\Shopify\Authentication
 */
abstract class AuthenticationAbstract implements Authentication
{
    protected const AUTHORIZATION_URI = 'https://%s.myshopify.com/admin/oauth/authorize';
    protected const ACCESS_URI = 'https://%s.myshopify.com/admin/oauth/access_token';

    /**
     * @var string $shopName
     */
    protected string $shopName;

    /**
     * @var string $clientId
     */
    protected string $clientId;

    /**
     * @var string $clientSecret
     */
    protected string $clientSecret;

    /**
     * @var array $scope
     */
    protected array $scope;

    /**
     * @var string $redirectUri
     */
    protected string $redirectUri;

    /**
     * @var HttpClient $httpClient
     */
    protected HttpClient $httpClient;

    /**
     * AuthenticationAbstract constructor.
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $state
     * @return string
     */
    public function getAuthenticationUri(string $state): string
    {

        $authorizeUri = sprintf(self::AUTHORIZATION_URI, $this->getShopName());

        $uriParams = [
            'client_id' => $this->getClientId(),
            'scope' => $this->getScope(),
            'redirect_uri' => $this->getRedirectUri()
        ];

        if ($this->getRedirectUri()) {
            $uriParams['redirect_uri'] = $this->getRedirectUri();
        }

        if ($state) {
            $uriParams['state'] = $state;
        }

        return $authorizeUri . '?' . http_build_query($uriParams);

    }

    /**
     * @return string
     */
    #[Pure] public function getAccessUri(): string
    {
        return sprintf(self::ACCESS_URI, $this->getShopName());
    }

    /**
     * @return bool
     */
    protected function canInitiateLogin(): bool
    {
        if (!$this->canBuildAuthenticationUri()) {
            throw new RuntimeException(
                'Cannot build authentication uri, dependencies are missing'
            );
        }

        return true;
    }

    /**
     * @return bool
     */
    #[Pure] protected function canBuildAuthenticationUri(): bool
    {
        return $this->getShopName() && $this->getClientId() && $this->getScope();
    }

    /**
     * @param string $temporaryToken
     * @return bool
     */
    #[Pure] protected function canAuthenticateShop(string $temporaryToken): bool
    {
        return $this->getClientId() && $this->getClientSecret() && $temporaryToken;
    }

    /**
     * @param string $shopName
     */
    protected function setShopName(string $shopName): void
    {
        $this->shopName = $shopName;
    }

    /**
     * @return string
     */
    protected function getShopName(): string
    {
        return $this->shopName;
    }

    /**
     * @param string $clientId
     */
    protected function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    protected function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientSecret
     */
    protected function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    protected function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param array $scope
     */
    protected function setScope(array $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * get the scope as a comma separated string
     * @return string
     */
    #[Pure] protected function getScope(): string
    {
        return implode(',', $this->scope);
    }

    /**
     * set the redirect URI
     * @param string $redirectUri
     */
    protected function setRedirectUri(string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * get the redirect uri
     * @return string
     */
    protected function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

}
