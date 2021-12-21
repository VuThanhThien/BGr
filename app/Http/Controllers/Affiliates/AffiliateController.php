<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Affiliate\AffiliateService;

class AffiliateController extends Controller
{
    private $affiliateService;
    public function __construct(AffiliateService $affiliateService){
        $this->affiliateService=$affiliateService;
    }
    public function getAffiliate($id){
        $affiliate=$this->affiliateService->getAffiliate($id);
        return $this->successResponse($affiliate, 'success', 200);
    }
    public function getNetworkStatistics()
    {
        $res = $this->affiliateService->getNetworkStatistics();
        return $this->successResponse($res, 'success', 200);
    }
    public function setCustomAffLink(Request $request)
    {
        $res = $this->affiliateService->setCustomAffLink($request->only('path','target'));
        return $this->successResponse($res, 'success', 200);
    }
    public function updateCustomAffLink(Request $request)
    {
        $res = $this->affiliateService->updateCustomAffLink($request->only('path'));
        return $this->successResponse($res, 'success', 200);
    }
}
