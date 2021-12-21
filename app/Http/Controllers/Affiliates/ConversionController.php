<?php

namespace App\Http\Controllers\Affiliates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Affiliate\ConversionService;


class ConversionController extends Controller
{
    const STATUS_APPROVED = 1;
    const STATUS_PENDING = 2;
    const STATUS_PAID = 3;
    const STATUS_REJECTED = 4;

    private ConversionService $conversionService;

    public function __construct(ConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }

    public function index(Request $request)
    {
        $conversions = $this->conversionService->getListWithPaginate( $request->all() );
        return $this->successResponse($conversions, 'success', 200);
    }
    public function getStatistics(Request $request){
        $statistics=$this->conversionService->getStatistics($request->all());
        return $this->successResponse($statistics, 'success', 200);
    }  
    
    public function getCommissionStatistics(){
        $statistics=$this->conversionService->getCommissionStatistics();
        return $this->successResponse($statistics, 'success', 200);
    }
    
    public function getNetworkCommission($id)
    {
        $networkCommission = $this->conversionService->getNetworkCommission( $id );
        return $this->successResponse($networkCommission, 'success', 200);
    }
}
