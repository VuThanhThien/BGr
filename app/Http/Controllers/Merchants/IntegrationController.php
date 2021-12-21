<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use App\Services\IntegrationService;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    private IntegrationService $integrationService;
    public function __construct(IntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    public function toggleSyncKlaviyoApprovedAffiliate()
    {
        $result = $this->integrationService->toggleSyncKlaviyoApprovedAffiliate();
        return $this->successResponse($result, 'success', 200);
    }

    public function getListKlaviyo(Request $request)
    {
        $result = $this->integrationService->getListKlaviyo($request->only('klaviyo_api_key'));
        return $this->successResponse($result, 'success', 200);
    }

    public function saveSyncKlaviyo(Request $request)
    {
        $result = $this->integrationService->saveSyncKlaviyo($request->all());
        return $this->successResponse($result, 'success', 200);
    }
    
    public function toggleKlaviyoSyncEnabled(Request $request)
    {
        $result = $this->integrationService->toggleKlaviyoSyncEnabled($request->all());
        return $this->successResponse($result, 'success', 200);
    }

    public function syncKlaviyo()
    {
        $result = $this->integrationService->syncKlaviyo();
        return $this->successResponse($result, 'success', 200);
    }
}
