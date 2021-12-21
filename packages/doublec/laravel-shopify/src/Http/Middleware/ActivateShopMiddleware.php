<?php

namespace DoubleC\LaravelShopify\Http\Middleware;

use Closure;
use DoubleC\LaravelShopify\Traits\InstalledShop;
use DoubleC\LaravelShopify\Traits\ShopCharge;
use Illuminate\Http\Request;

class ActivateShopMiddleware
{
    use InstalledShop;
    use ShopCharge;

    /**
     * Handle an incoming request.
     * Check shop not uninstall & have not deactivated (subscription)
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->shopService->isUninstalled($this->shop())) {
            return response()->view('laravel-shopify::pages.errors', [
                "messages" => [
                    'Sorry please try again. (Your shop has removed the app)'
                ]
            ]);
        }
        $subscription = $this->shopPlan();
        if (!$subscription || $subscription->isCanceled()) {
            return $this->billing();
        }
        return $next($request);
    }
}
