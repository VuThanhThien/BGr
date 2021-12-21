<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LayoutService;

class LayoutController extends Controller
{

    private LayoutService $layoutService;

    public function __construct(LayoutService $layoutService)
    {
        $this->layoutService = $layoutService;
    }

    public function getData(Request $request)
    {
        $data = $this->layoutService->getData();
        return $this->successResponse($data, 'success', 200);
    }

}
