<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewAffiliate;
use App\Services\DashboardService;

class DashboardController extends Controller
{

    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
       
    }

    public function getGrowthStatistic(Request $request)
    {
        $period= $request->only('period');
        $data = $this->dashboardService->getGrowthStatistic($period);
        return $this->successResponse($data, 'success', 200);
    }

    public function getInitData(Request $request)
    {
        $data = $this->dashboardService->getInitData();
        return $this->successResponse($data, 'success', 200);
    }
    public function getPerformance(Request $request)
    {
        $data = $this->dashboardService->getPerformance($request->all());
        return $this->successResponse($data, 'success', 200);
    }

    public function getTopAffiliate(Request $request)
    {
        $topAffiliate = $this->dashboardService->topAffiliate($request->all());
        return $this->successResponse($topAffiliate, 'success', 200);
    }

    public function getRecentSale(Request $request)
    {
        $topRecentSale = $this->dashboardService->getRecentSale($request->all());
        return $this->successResponse($topRecentSale, 'success', 200);
    }
}
