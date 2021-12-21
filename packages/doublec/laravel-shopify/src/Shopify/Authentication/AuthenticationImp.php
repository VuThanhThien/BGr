<?php

namespace DoubleC\LaravelShopify\Shopify\Authentication;


use http\Exception\RuntimeException;
use Illuminate\Http\RedirectResponse;

class AuthenticationImp extends AuthenticationAbstract {

    public function forShopName(string $shopName): Authentication
    {
        $this->setShopName($shopName);
        return $this;
    }

    public function usingClientId(string $clientId): Authentication
    {
        $this->setClientId($clientId);
        return $this;
    }

    public function usingClientSecret(string $clientSecret): Authentication
    {
        $this->setClientSecret($clientSecret);
        return $this;
    }

    public function withScope(array $scope): Authentication
    {
        $this->setScope($scope);
        return $this;
    }

    public function redirectTo(string $uri): Authentication
    {
        $this->setRedirectUri($uri);
        return $this;
    }

    public function initiateLogin(string $state): RedirectResponse
    {
        if (!$this->canInitiateLogin()) {
            throw new RuntimeException(
                'Unable to initiate login'
            );
        }

        $authorizeUri = $this->getAuthenticationUri($state);
        return redirect()->to($authorizeUri);
    }

    public function toPermanentToken(string $temporaryToken): string
    {
        if (!$temporaryToken) {
            throw new RuntimeException(
                'Missing shopify code'
            );
        }

        if (!$this->canAuthenticateShop($temporaryToken)) {
            throw new RuntimeException(
                'Cannot authenticate user, dependencies are missing'
            );
        }

        $requestParams = [
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'code' => $temporaryToken
        ];

        $response = json_decode($this->httpClient->post($this->getAccessUri(), $requestParams));

        if (isset($response->error)) {
            throw new RuntimeException($response->error);
        }

        return isset($response->access_token) ? $response->access_token : '';
    }
}
