<?php

namespace DoubleC\LaravelShopify\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ShopAuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     * Check shop is logged
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth()->check()) {
            $message = 'You have not logged in or your session was expired, please login from your app admin';
            if ($request->ajax() || $request->wantsJson()) {
                response()->json(['errors' => [$message]], 419);
            }
            return response()->view('laravel-shopify::pages.errors', [
                'messages' => [$message]
            ]);
        }
        return $next($request);
    }
}
