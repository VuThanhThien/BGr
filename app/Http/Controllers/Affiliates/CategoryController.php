<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use App\Services\Affiliate\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function getFileCategory(Request $request,$id)
    {
        $result = $this->categoryService->getFileCategory($request->all(),$id);
        return $this->successResponse( $result,'success',200);
    }
}
