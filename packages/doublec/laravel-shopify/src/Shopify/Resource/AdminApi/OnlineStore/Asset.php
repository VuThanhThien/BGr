<?php

namespace DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore;

use DoubleC\LaravelShopify\Shopify\Resource\BaseResource;


class Asset extends BaseResource
{

    /**
     * Retrieve a list of all assets for a theme
     * @param int $themeId
     * @param array $params
     * @return object|array|null
     */
    public function all(int $themeId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->get("themes/$themeId/assets.json", $params));
    }

    /**
     * Creates or updates an asset for a theme.
     * @param int $themeId
     * @param array $params
     * @return object|array|null
     */
    public function update(int $themeId, array $params): object|array|null
    {
        return $this->prepareResponse($this->shopifyApi->put("themes/$themeId/assets.json", $params));
    }

    /**
     * Deletes an asset from a theme.
     * @param int $themeId
     * @param array $params
     * @return object|array|null
     */
    public function delete(int $themeId, array $params = []): object|array|null
    {
        $endpoint = "themes/$themeId/assets.json";

        if ($params) {
            $endpoint .= '?' . http_build_query($params);
        }

        return $this->prepareResponse(
            $this->shopifyApi->delete($endpoint)
        );
    }

}
