<?php

namespace App\Services\Affiliate;

use App\Models\AffiliateCoupon;
use Illuminate\Support\Facades\Auth;
use DB;

class AffiliateCouponService {
    public function getAffiliateCoupon(){
        $affiliate=auth('affiliate-api')->user();
        $affiliateCoupon=AffiliateCoupon::where('affiliate_id', $affiliate->id);
        $affiliateCoupon= $affiliateCoupon->with('affiliate:id,first_name,last_name,email')->paginate(4);
        return  $affiliateCoupon;
    }
}
