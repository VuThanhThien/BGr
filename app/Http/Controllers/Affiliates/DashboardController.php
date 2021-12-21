<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\ScriptTag;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\Events\Webhook;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use App\Services\Affiliate\DashboardService;
use App\Traits\ApiResponser;

class DashboardController extends Controller
{

    use ApiResponser;
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index($subdomain)
    {
       dd($subdomain);
    }

    public function getInitData()
    {
        $data = $this->dashboardService->getInitData();
        return $this->successResponse($data, 'success', 200);
    }
    public function getPerformance(Request $request)
    {
        $data = $this->dashboardService->getPerformance($request->all());
        return $this->successResponse($data, 'success', 200);
    }
    public function getLogoShop(Request $request)
    {
        $origin = $request->header('origin');
        $data = $this->dashboardService->getLogoShop($origin);
        return $this->successResponse($data, 'success', 200);
    }
}

