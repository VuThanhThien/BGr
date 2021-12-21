<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AffiliateTierService;

class AffiliateTierController extends Controller
{
    private AffiliateTierService $affiliateTierService;

    public function __construct(AffiliateTierService $affiliateTierService)
    {
        $this->affiliateTierService = $affiliateTierService;
    }

    public function index(Request $request)
    {
        $affiliateTier = $this->affiliateTierService->get();
        return $this->successResponse($affiliateTier, 'success', 200);
    }

    public function updateAffiliateTier(Request $request)
    {
        $this->affiliateTierService->updateAffiliateTier($request->all());
        return $this->successResponse(true, 'success', 200);
    }
  
}
