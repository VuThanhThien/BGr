<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductCommissionService;

class ProductCommissionController extends Controller
{

    private productCommissionService $productCommission;

    public function __construct(ProductCommissionService $productCommissionService)
    {
        $this->productCommissionService = $productCommissionService;
    }

    public function index(Request $request) 
    {
        $products = $this->productCommissionService->getProducts($request->all());
        return $this->successResponse($products, 'success', 200);
    }

    public function update(Request $request, $id) 
    {
        $product = $this->productCommissionService->update($id, $request->all());
        return $this->successResponse($product, 'success', 200);
    }

    public function destroy(Request $request, $id)
    {
        $this->productCommissionService->destroy($id);
        return $this->successResponse('', 'success', 200);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->get('ids');
        $this->productCommissionService->bulkDelete($ids);
        return $this->successResponse('', 'success', 200);
    }

    public function searchShopifyProduct(Request $request)
    {
        $query = $request->get('query');
        $products = $this->productCommissionService->searchShopifyProduct($query);
        return \Response::json($products);
    }

    public function searchShopifyCollection(Request $request)
    {
        $query = $request->get('query');
        $collections = $this->productCommissionService->searchShopifyCollection($query);
        return \Response::json($collections);
    }
    
    public function store(Request $request)
    {
        $res = $this->productCommissionService->store($request->all());
        return $this->successResponse('', 'success', 200);
    }

}
