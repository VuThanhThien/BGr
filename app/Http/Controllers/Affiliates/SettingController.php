<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Affiliate\SettingService;
use App\Traits\ApiResponser;

class SettingController extends Controller
{

    use ApiResponser;
    private SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        
        $res = $this->settingService->getSetting();
        return $this->successResponse($res, 'success', 200);
    }

    public function updatePaymentMethod(Request $request)
    {
        $res = $this->settingService->updatePaymentMethod($request->all());
        return $this->successResponse($res, 'success', 200);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed'
        ]);
        $res = $this->settingService->updatePassword($request->all());
        return $res;
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
        $user = $this->settingService->updateProfile($request->all());
        return $this->successResponse($user, 'success', 200);
    }
}
