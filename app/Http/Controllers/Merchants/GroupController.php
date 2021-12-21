<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GroupService;

class GroupController extends Controller
{

    private GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index(Request $request)
    {
        $groups = $this->groupService->getList($request->all());
        return $this->successResponse($groups, 'success', 200);
    }

    public function store(Request $request)
    {
        $group = $this->groupService->store($request->all());
        return $this->successResponse($group, 'success', 200);
    }

    public function show($id)
    {
        $group = $this->groupService->findById($id);
        $group->registration_form = synConfigUpdateRegistrationData($group->registration_form);
        return $this->successResponse($group, 'success', 200);
    }

    public function update(Request $request, $id)
    {
        $group = $this->groupService->update($request->all(), $id);
        return $this->successResponse($group, 'success', 200);
    }

    public function updateMLM(Request $request, $id)
    {
        $group = $this->groupService->updateMLM($request->all(), $id);
        return $this->successResponse($group, 'success', 200);
    }
    public function destroy($id)
    {
        $res = $this->groupService->delete($id);
        if($res)
        {
            return $this->successResponse($res,'success',200);
        }
        return $this->errorResponse('Can not delete program when used for affiliate tiers settings.', 403);
       
    }
    public function checkAffilate($id)
    {
        $numberAffiliate=$this->groupService->countAffilate($id);
        return $this->successResponse(['count' => $numberAffiliate],'success',200);


    }

    public function changeActive($id)
    {
        $res=$this->groupService->changeActive($id);
        return $this->successResponse($res,'success',200);
    }

    public function setDefault($id)
    {
        $res=$this->groupService->setDefault($id);
        return $this->successResponse($res,'success',200);
    }

    public function updateRegistrationForm(Request $request, $id)
    {
        $res=$this->groupService->updateRegistrationForm($request->all(), $id);
        return $this->successResponse($res,'success',200);
    }
    public function uploadAsideImage(Request $request)
    {
        $pathLogo=$this->groupService->uploadAsideImage($request->logo,$request->file('logo'));
        return $this->successResponse($pathLogo,'success',200);
    }

    public function duplicate(Request $request)
    {
        $newGroup = $this->groupService->duplicate($request->all());
        return $this->successResponse($newGroup,'success',200);
    }

    public function getAutomaticCouponCustomer(Request $request)
    {
        $couponCode = $this->groupService->getAutomaticCouponCustomer($request->only('shop','affiliateId'));
        return response()->json($couponCode);
    }
}
