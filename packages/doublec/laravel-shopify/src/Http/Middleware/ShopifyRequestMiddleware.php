<?php

namespace DoubleC\LaravelShopify\Http\Middleware;

use Closure;
use DoubleC\LaravelShopify\Services\AuthenticateApi\AuthenticateApi;
use Illuminate\Http\Request;

class ShopifyRequestMiddleware
{
    public function __construct(
        protected AuthenticateApi $authenticateApi
    )
    {
    }

    /**
     * Handle an incoming request.
     * Check is valid request from shopify
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->authenticateApi->isValidShopifyRequest($request->all())) {
            return $next($request);
        }
        return response()->view('laravel-shopify::pages.errors', [
            'messages' => [
                'Sorry please try again. (Invalid shopify request)'
            ]
        ]);
    }
}
