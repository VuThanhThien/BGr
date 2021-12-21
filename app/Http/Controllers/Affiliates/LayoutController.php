<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Affiliate\LayoutService;

class LayoutController extends Controller
{

    private LayoutService $LayoutService;

    public function __construct(LayoutService $layoutService)
    {
        $this->layoutService = $layoutService;
    }

    public function getData(Request $request)
    {
        $data = $this->layoutService->getData();
        return $this->successResponse($data, 'success', 200);
    }

    public function getTranslations($locale = null)
    {
        $translations = $this->layoutService->getTranslations($locale);
        return  $this->successResponse($translations, 'success', 200);
    }

    public function getAffiliateAcountLanguage()
    {
        $res = $this->layoutService->getAffiliateAcountLanguage();
        return  $this->successResponse($res, 'success', 200);
    }

}
