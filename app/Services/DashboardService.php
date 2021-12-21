<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\ShopInfo;
use App\Models\Conversion;
use App\Models\Click;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

Class DashboardService
{

    public function getGrowthStatistic($period)
    {
        $now = now();
        if( $period == '24h' ){
            $from = $now->subHours(24);
        }else if( $period == '7d' ) {
            $from = $now->subDays(7);
        }else{
            $from = $now->subDays(30);
        }
        $clicks = Click::whereBetween('date', [$from,$now])->sum('count');
        dd($from);

    }

    public function getInitData()
    {
        
        $shopId = Auth::user()->shop_id;
        $shop = Shop::where('id', $shopId)->with('info:shop_id,currency,money_format')->select('id', 'shop')->first();
        return [
            'shop' => $shop
        ];
    }
    public function getPerformance($data)
    {
        $shopId=auth()->user()->shop_id;
        $startDate = \Carbon\Carbon::createFromTimestamp(intval($data['start_date']))->toDateTimeString();
        $endDate = \Carbon\Carbon::createFromTimestamp(intval($data['end_date']))->toDateTimeString();
        if($data['type']=='clicks')
        {
          
            $click=Click::whereHas('affiliate',function($query) use($data,$shopId)
                {
                        if(isset($data['group']))
                        {
                            $query->whereHas('shop',function($query) use($shopId) {
                                $query->where('id',$shopId);
                            })->whereHas('group',function($query) use($data){
                                $query->where('id',$data['group']);
                            });
                        }
                        else{
                            $query->whereHas('shop',function($query) use($shopId){
                                    $query->where('id',$shopId);
                            });
                        }
                })->select([DB::raw('sum(count) as clicks'),DB::raw('date as times')])->groupBy('date')
                ->whereBetween('date',[$startDate,$endDate])->get();                 
            return [
                'line'=>  $click,
                'type'=>'clicks'
              ];
        }
        else{
            $converstion=Conversion::where('shop_id',$shopId)->where('level',0);
            if(isset($data['group']))
            {
                $converstion=$converstion->where('group_id',$data['group']);
            }
            if($data['type']=='orders')
            {        
                $orders=$converstion->whereBetween('created_at',[$startDate,$endDate])
                ->select([DB::raw('count(*) as orders'),DB::raw('DATE(created_at) as times')])->groupBy(DB::raw('times'))->get();
                return [
                    'line'=>  $orders,
                    'type'=>'orders'
                  ];
            } else {      
                $sales=$converstion->whereBetween('created_at',[$startDate,$endDate])->select([DB::raw('sum(total) as sales'),DB::raw('DATE(created_at) as times')])
                ->groupBy(DB::raw('times'))->get();
                }
                return [
                    'line'=> $sales,
                    'type'=>'sales',
                  ];
            }     
    }

    public function topAffiliate($requestData)
    {
        $shopId = Auth::user()->shop_id;
        $conditions = [['conversions.shop_id', '=', $shopId]];
        $startDate = \Carbon\Carbon::createFromTimestamp(intval($requestData['start_date']))->toDateTimeString();
        $endDate = \Carbon\Carbon::createFromTimestamp(intval($requestData['end_date']))->toDateTimeString();
        $select = [ DB::Raw('SUM(CASE WHEN conversions.created_at BETWEEN "'.$startDate.'" AND "' .$endDate. '" THEN conversions.total END) as sales'), 
        DB::Raw('SUM(CASE WHEN conversions.created_at BETWEEN "'.$startDate.'" AND "' .$endDate. '" THEN conversions.commission END) as total_commission'),
        DB::Raw('SUM(CASE WHEN conversions.created_at BETWEEN "'.$startDate.'" AND "' .$endDate. '" AND conversions.level=0 THEN 1 ELSE 0 END) as total_conversion'),
        'conversions.affiliate_id', 'affiliates.id', 'affiliates.first_name', 'affiliates.last_name'];
        $topAffiliate = Conversion::join('affiliates', 'affiliates.id', '=', 'conversions.affiliate_id')
                                    ->select($select)
                                    ->where($conditions)->whereBetween('conversions.created_at',[$startDate,$endDate])
    								->groupBy('conversions.affiliate_id','affiliates.id','affiliates.first_name','affiliates.last_name')
                                    ->orderBy($requestData['sort_field'], 'desc')->take(5)
                                    ->get();

        return $topAffiliate;

    }

    public function getRecentSale($data)
    {
        $shopId = Auth::user()->shop_id;
        $conditions = [['conversions.shop_id', '=', $shopId]];
        $conditions[] = ['level', 0];
        $conversions=Conversion::where($conditions);
        if(isset($data['start_date'])) {
            $startDate = Carbon::createFromTimestamp( intval($data['start_date']) )->toDateTimeString();
            $endDate = Carbon::createFromTimestamp( intval($data['end_date']) )->toDateTimeString();
            $conversions = $conversions->whereBetween('conversions.created_at', [$startDate, $endDate]);
        }
        $conversions = $conversions->with('affiliate:id,first_name,last_name')->orderBy($data['sort_field'], 'desc')->take(5)
        ->get();
        return $conversions;
    }
}
