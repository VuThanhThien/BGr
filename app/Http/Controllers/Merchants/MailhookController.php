<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MailhookService;

class MailhookController extends Controller
{
    private MailhookService $mailhookService;
    public function __construct(MailhookService $mailhookService)
    {
       $this->mailhookService = $mailhookService;
    }

    public function updateMailOutbox(Request $request)
    {
        $result = $this->mailhookService->updateMailOutbox($request->getContent());
        return $this->successResponse($result,'success',200);

    }
}
