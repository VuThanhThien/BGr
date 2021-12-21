<?php

namespace DoubleC\LaravelShopify\Http\Controllers;

use DoubleC\LaravelShopify\Http\Requests\HasPlanIdRequest;
use DoubleC\LaravelShopify\Repositories\PlanRepository\PlanRepository;
use DoubleC\LaravelShopify\Traits\ShopSubscription;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class BillingController extends Controller
{
    use ShopSubscription;

    /**
     * Get plan list
     * @return Factory|View|Application
     */
    public function plans(): Factory|View|Application
    {
        $plans = $this->planRepository->getActivePlans();
        return view('laravel-shopify::pages.plans', compact('plans'));
    }

    /**
     * Subscribe plan from pricing list
     * @param HasPlanIdRequest $request
     * @return RedirectResponse
     */
    public function subscribePlan(HasPlanIdRequest $request): RedirectResponse
    {
        return $this->shopSubscribe($request->get('plan_id'));
    }
}
