<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use App\Services\GroupService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class QuickStartController extends Controller
{
    private GroupService $groupService;
    private SettingService $settingService;
    public function __construct(GroupService $groupService,SettingService $settingService)
    {
        $this->groupService=$groupService;
        $this->settingService=$settingService;
    }
    public function defaultGroup()
    {
        $group =  $this->groupService->defaultGroup();
        return $this->successResponse($group, 'success', 200);
    }
    public function uploadLogo(Request $request)
    {
        $pathLogo=$this->settingService->uploadLogo($request->logo,$request->file('logo'));
        return $this->successResponse($pathLogo,'success',200);
    }
    public function uploadLogoShop(Request $request)
    {
        $shopSettings=$this->settingService->uploadLogoShop($request->only('logo','path_name'));
        return $this->successResponse($shopSettings,'success',200);
    }
}
