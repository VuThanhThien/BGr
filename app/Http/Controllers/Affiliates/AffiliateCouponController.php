<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Services\Affiliate\AffiliateCouponService;

class AffiliateCouponController extends Controller
{
    private AffiliateCouponService $affiliateService;
    public function __construct(AffiliateCouponService $affiliateService)
    {
        $this->affiliateService = $affiliateService;
    }
    public function getAffiliateCoupon()
    {
        $affiliateCoupon=$this->affiliateService->getAffiliateCoupon();
        return $this->successResponse( $affiliateCoupon, 'success', 200);
    }

}
