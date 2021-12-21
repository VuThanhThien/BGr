<?php

namespace DoubleC\LaravelShopify\Http\Middleware;

use Closure;
use DoubleC\LaravelShopify\Traits\InstalledShop;
use Illuminate\Http\Request;

class AdminShopMiddleware
{
    use InstalledShop;

    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!in_array($this->shop()->shop, config('shopify.shopify_sudo_store')))
            abort(404);
        return $next($request);
    }
}
