<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TrackingService;

class ApiController extends Controller
{
    private $trackingService;

    public function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function trackEvent(Request $request)
    {
        $data = $request->only('aff_id','event_type', 'client_id', 'cart_token', 'checkout_token', 'shop');
        $res = $this->trackingService->create($data);
        return response()->json($res);

    }
}
