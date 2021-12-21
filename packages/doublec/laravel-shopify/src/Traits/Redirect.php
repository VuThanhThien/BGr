<?php

namespace DoubleC\LaravelShopify\Traits;

trait Redirect {

    public function index(): string
    {
        return redirect()->route(config('shopify.app_url'));
    }

}
