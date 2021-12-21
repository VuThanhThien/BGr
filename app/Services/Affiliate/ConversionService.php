<?php

namespace App\Services\Affiliate;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\Conversion;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

Class ConversionService
{
    public function getListWithPaginate($data)
    {
        $user = auth('affiliate-api')->user();
        $conditions = [['conversions.affiliate_id', '=', $user->id]];
        if( isset($data['affiliate']) ) {
            $conditions[] = ['affiliate_id', $data['affiliate']];
        }
        if( isset($data['status']) ) {
            $conditions[] = ['status', $data['status']];
        }
        
        $conversions = Conversion::where($conditions);

        if(isset($data['start_date'])) {
            $startDate = Carbon::createFromTimestamp( intval($data['start_date']) )->toDateTimeString();
            $endDate = Carbon::createFromTimestamp( intval($data['end_date']) )->toDateTimeString();
            $conversions = $conversions->whereBetween('conversions.created_at', [$startDate, $endDate]);
        }

        $conversions = $conversions->with('affiliate:id,first_name,last_name')->paginate($data['paginate']);
        return $conversions;
    }
    public function getStatistics($data){
        $affiliate = auth('affiliate-api')->user();
        $affiliateId=$affiliate->id;
        $startDate = Carbon::createFromTimestamp( intval($data['start_date']) )->toDateTimeString();
        $endDate = Carbon::createFromTimestamp( intval($data['end_date']) )->toDateTimeString();
        $clicks = $affiliate->clicks()->whereBetween('date',[$startDate,$endDate])->sum('count');
        $res = Conversion::where('affiliate_id', $affiliateId)->whereBetween('created_at',[ $startDate,  $endDate])
        ->select([\DB::Raw('sum(total) as revenue'), \DB::Raw('sum(commission) as commission'), \DB::Raw('count(*) as orders')])
        ->groupBy('affiliate_id')
        ->first();
        return [
            'clicks' => $clicks,
            'commission' => $res ? $res->commission : 0,
            'orders' => $res ? $res->orders : 0,
            'revenue' => $res ? $res->revenue: 0
        ];
    }
    public function getCommissionStatistics()
    {
        $affiliateId=auth('affiliate-api')->user()->id;
        $statistics=Conversion::where('affiliate_id',$affiliateId)
        ->select(DB::raw('sum(case when status=2 then commission else 0 end) as commission_pending'),
                DB::raw('sum(case when status=1 then commission else 0 end) as commission_approved'),
                DB::Raw('sum(case when status=3 then commission else 0 end) as commission_paid'),
                DB::Raw('sum(case when status=4 then commission else 0 end) as commission_rejected')   
        )->first();
        if(!isset($statistics['commission_pending']))
        {
            $statistics=[
                'commission_pending'=>'0.00',
                'commission_approved'=>'0.00',
                'commission_paid'=>'0.00',
                'commission_rejected'=>'0.00',
                'timestamp'=>''
            ];
        }
        return $statistics;
    }

    public function getNetworkCommission($id){
        $conversion = Conversion::where('id',$id)->with('affiliate:id,first_name,last_name')->first();
        return $conversion;
    }
    
}
