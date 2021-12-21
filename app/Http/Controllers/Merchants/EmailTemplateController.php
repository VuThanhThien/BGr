<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmailTemplateService;
use App\Traits\ApiResponser;
class EmailTemplateController extends Controller
{
    use ApiResponser;

    private EmailTemplateService $emailTemplateService;

    public function __construct(EmailTemplateService $emailTemplateService)
    {
        $this->emailTemplateService = $emailTemplateService;
    }

    public function getEmailTemplate($slug){

        $emailTemplate= $this->emailTemplateService->getEmailTemplate($slug);
        return $this->successResponse($emailTemplate,'success',200);

    }

    public function store(Request $request)
    {
        $emailTemplate= $this->emailTemplateService->createOrUpdate($request->all());
        return $this->successResponse($emailTemplate,'success',200);

    }
    public function getEmailTemplateStatusByShopId(){
        $listEmailTemplateStatus=$this->emailTemplateService->getEmailTemplateStatusByShopId();
        return $this->successResponse($listEmailTemplateStatus,'success',200);
    }

    public function changeStatusEmailTemplate(Request $request){
        $emailTemplate= $this->emailTemplateService->changeStatusEmailTemplate($request->only('slug','status'));
        return $this->successResponse($emailTemplate,'success',200);
    }

    public function bulkEmail(Request $request){
        $true= $this->emailTemplateService->bulkEmail($request->all());
        return $this->successResponse($true,'success',200);
    }

    public function getMailOutbox(Request $request){
        $mailOutbox = $this->emailTemplateService->getMailOutbox($request->all());
        return $this->successResponse($mailOutbox,'success',200);
    }

    public function uploadEmailImage(Request $request)
    {
        $path = $this->emailTemplateService->uploadEmailImage($request->image,$request->file('image'));
        return $this->successResponse($path,'success',200);
    }

}
