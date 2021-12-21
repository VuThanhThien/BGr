<?php

namespace DoubleC\LaravelShopify\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShopifyValidWebhookMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (isValidWebhookRequest($request)) {
            return $next($request);
        }
        return response()->json([
            "message" => "Request invalid !"
        ], 403);
    }
}
