<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ConversionService;

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

    public function approve(Request $request, $id)
    {
        $conversion = $this->conversionService->updateStatus( $id, self::STATUS_APPROVED );
        return $this->successResponse($conversion, 'success', 200);
    }
    public function reject(Request $request, $id)
    {
        $conversion = $this->conversionService->updateStatus( $id, self::STATUS_REJECTED );
        return $this->successResponse($conversion, 'success', 200);
    }
    public function undo(Request $request, $id)
    {
        $conversion = $this->conversionService->updateStatus( $id, self::STATUS_PENDING );
        return $this->successResponse($conversion, 'success', 200);
    }
    public function getStatistics(Request $request)
    {
        $statistics=$this->conversionService->getStatistics($request->all());
        return $this->successResponse($statistics,'success',200);
    }

    public function getApprovedConversionByAffiliate()
    {
        
    }
    public function getCommissionStatistics(){
        $statistics=$this->conversionService->getCommissionStatistics();
        return $this->successResponse($statistics, 'success', 200);
    }       
    public function approveConversions(Request $request)
    {
        $conversions = $this->conversionService->approveConversions($request->only('conversionsId'), self::STATUS_APPROVED );
        return $this->successResponse($conversions, 'success', 200);
    }
    public function denyConversions(Request $request)
    {
        $conversion = $this->conversionService->denyConversions($request->only('conversionsId'), self::STATUS_REJECTED );
        return $this->successResponse($conversion, 'success', 200);
    }
    public function networkExplanation($id)
    {
        $conversion = $this->conversionService->networkExplanation($id);
        return $this->successResponse($conversion, 'success', 200);
    }

    public function store(Request $request)
    {
       
        return  $this->conversionService->store($request->all());
    }
    public function saveComment(Request $request,$id)
    {
        $result = $this->conversionService->saveComment($request->only('comment'),$id);
        return $this->successResponse($result, 'success', 200);
    }

}
