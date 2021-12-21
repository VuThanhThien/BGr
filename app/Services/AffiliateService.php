<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Group;
use App\Models\Shop;
use App\Models\Conversion;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Events\ApprovedAffiliateEvent;
use App\Events\DeniedAffiliateEvent;
use App\Models\PaymentMethod;
use App\Models\ShopPaymentMethod;
use App\Models\ShopSetting;
use Carbon\Carbon;
use DoubleC\LaravelShopify\Shopify\ClientApi;
use DoubleC\LaravelShopify\Shopify\Resource\AdminApi\OnlineStore\Redirect;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;

Class AffiliateService
{
    use ApiResponser;
    public function getAffiliateWithPaginate($requestData)
    {
        $shopId = Auth::user()->shop_id;
        $conditions = [['affiliates.shop_id', '=', $shopId]];
        if(isset($requestData['affiliate']))
        {
            $conditions[] = ['id',$requestData['affiliate']];
        }
        if( isset($requestData['group']) ) {
            $conditions[] = ['affiliates.group_id', $requestData['group']];
        }
        if( isset($requestData['status']) ) {
            $conditions[] = ['affiliates.status', $requestData['status']];
        }

        $affiliates = Affiliate::where($conditions);

        if(isset($requestData['start_date'])) {
            $startDate = \Carbon\Carbon::createFromTimestamp( intval($requestData['start_date']) )->toDateTimeString();
            $endDate = \Carbon\Carbon::createFromTimestamp( intval($requestData['end_date']) )->toDateTimeString();
            $affiliates = $affiliates->whereBetween('affiliates.created_at', [$startDate, $endDate]);
        }
        $affiliates = $affiliates->with(['group:id,name', 'setting'])
        ->orderBy($requestData['sort_field'], $requestData['sort_direction'])
        ->paginate($requestData['paginate']);
        return $affiliates;
    }

    public function getAffiliate($id)
    {
        $affiliate = Affiliate::where('id', $id)->with('parent:id,first_name,last_name,email')->first();
        if(!auth()->user()->can('getAffiliate',$affiliate)){
            throw new \Illuminate\Auth\Access\AuthorizationException();         
        }
        return $affiliate;
      
    }

    public function search($data)
    {
        $shopId = auth()->user()->shop_id;
        $query = addslashes($data["query"]);
        $affiliate = Affiliate::where('shop_id', $shopId)->where(function ($q) use ($query) {
            $q->where('first_name', 'like', '%' . $query . '%')
                ->orWhere('last_name', 'like', '%' . $query . '%')
                ->orWhereRaw("concat(first_name, ' ', last_name) like '%" . $query . "%' ")
                ->orWhere('email', 'like', '%' . $query . '%');
        });
        
        $affiliate = $affiliate->select('id', 'email', 'first_name', 'last_name')->get();

        return $affiliate;
    }

    public function changeStatus($requestData)
    {
        $affiliate = Affiliate::find($requestData['affiliate_id']);
        if (!auth()->user()->can('view', $affiliate)) {
           throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        $affiliate->status = $requestData['status'];
        $affiliate->save();
        if($requestData['status'] == 1) {
            event(new ApprovedAffiliateEvent($affiliate));
        }
        if($requestData['status'] == 3) {
            event(new DeniedAffiliateEvent($affiliate));
        }
        return true;
    }

    public function destroy($id)
    {
        $affiliate=Affiliate::find($id);
        if(!auth()->user()->can('delete',$affiliate))
        {
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        $affiliate->delete();
        return true;

    }

    public function getStatistics($id)
    {
        $affiliate = Affiliate::findOrFail($id);
        if(!auth()->user()->can('getStatistics',$affiliate)){
            throw new \Illuminate\Auth\Access\AuthorizationException();
        }
        $clicks = $affiliate->clicks()->sum('count');
        $res = Conversion::where('affiliate_id', $id)
        ->select([DB::Raw('sum(total) as revenue'), DB::Raw('sum(commission) as commission'), DB::Raw('count(*) as orders')])
        ->groupBy('affiliate_id')
        ->first();
        return [
            'clicks' => $clicks,
            'commission' => $res ? $res->commission : 0,
            'orders' => $res ? $res->orders : 0,
            'revenue' => $res ? $res->revenue: 0
        ];

    }

    public function getCommissionStatistics($id)
    {
        $statistics = Conversion::where('affiliate_id', $id)
    	->select(DB::Raw('sum(case when status=2 then commission else 0 end) as commission_pending'), 
                DB::Raw('sum(case when status=1 then commission else 0 end) as commission_approved'),
                DB::Raw('sum(case when status=3 then commission else 0 end) as commission_paid'),
                DB::Raw('sum(case when status=4 then commission else 0 end) as commission_rejected'))
    	->first();
        return $statistics;
                    
    }

    public function setParent($id, $parentId)
    {
        if($id == $parentId)
        {
            return [
                'status' => false,
                'message' => 'You cannot set affiliates to also be their parent'
            ];
        }
        $affiliate = $this->getAffiliate($id);
        if($parentId == 0) {
            $affiliate->parent_id = $parentId;
            $affiliate->save();
            $affiliate->refresh();
            return [
                'status' => true,
                'affiliate' => $affiliate
            ];
        }else{
            $parent = Affiliate::findOrFail($parentId);
            $granId = $parent->parent_id;
            while ($granId != 0) {
                $gran = Affiliate::find($granId);
                if ($gran->id == $id) {
                    return [
                        'status' => false,
                        'message' => 'Cannot choose an existing downline affiliate to be an upline affiliate'
                    ];
                }
                $granId = $gran->parent_id;
            }

            $affiliate->parent_id = $parentId;
            $affiliate->save();
            $affiliate->refresh();
            return [
                'status' => true,
                'affiliate' => $affiliate
            ];

        }
        
        
    }

    public function getNetworkStatistics($id)
    {
           $affiliate = Affiliate::findOrFail($id);
            if(!auth()->user()->can('getNetworkStatistics',$affiliate))
            {
                throw new \Illuminate\Auth\Access\AuthorizationException();    
            }
           $group = Group::where('id', $affiliate->group_id)->select('id', 'mlm_tree')->first();
            $mlmTree = $group->mlm_tree;
           $levels = [];
           $dataLevel = [];
           $earnings = 0;
           $totalSignups = 0;
   
           // $networkLink = url('/').'/'.$domain.'/register?ref='.$affiliate->hash_code;
   
           if ($mlmTree) {
               for ($i = 1; $i <= count($mlmTree); $i++) {
                   $levels[] = $i;
               }
               $infos = Conversion::where('affiliate_id', $affiliate->id)
                   ->whereIn('level', $levels)
                   ->select('level', DB::raw('SUM(total) as total_revenue'), DB::raw('COUNT(*) as total_conversion'), DB::raw('SUM(commission) as total_commission'))
                   ->groupBy('level')
                   ->orderBy('level')
                   ->get();
               $ids = [$affiliate->id];
   
               for ($i = 0; $i < count($mlmTree); $i++) {
                   $name = 'Level ' . ($i + 1);
                   $ids = Affiliate::whereIn('parent_id', $ids)->pluck('id')->toArray();
                   $dataLevel[] = ['level' => $name, 'total_signup' => count($ids), 'statistics' => $this->searchLevel($infos, $i + 1)];
   
               }
               
           }
   
   
           foreach ($dataLevel as $v) {
               if ($v['statistics']) {
                   $earnings = $earnings + $v['statistics']->total_commission;
               }
   
               $totalSignups = $totalSignups + $v['total_signup'];
           }

           return [
                'totalCommission' => $earnings,
                'totalDownline' => $totalSignups,
                'levelInfos' => $dataLevel
           ];
   
    }

    public function searchLevel($data, $level)
    {
        foreach ($data as $d) {
            if ($d['level'] == $level) return $d;
        }
        return null;
    }

    public function loginAs($id)
    {     
        $affiliate = Affiliate::findOrFail($id);
        if(!auth()->user()->can('loginAs',$affiliate))
        {
            throw new \Illuminate\Auth\Access\AuthorizationException();    
        }
        $token = auth('affiliate-api')->login($affiliate);
        return $token;
    }
    public function changeGroup($data,$id)
    {
        $affiliate=Affiliate::find($id);
        if(!auth()->user()->can('changeGroup',$affiliate))
        {
            throw new \Illuminate\Auth\Access\AuthorizationException();    
        }
        if($affiliate)
        {
            $affiliate->group_id=$data['group_id'];
            $affiliate->save();
        }
        return $affiliate;
    }
    public function removeGroup($data,$id)
    {
        $group=Group::withCount('affiliates')->find($id);
        if(!auth()->user()->can('removeGroup', $group))
        {
            throw new \Illuminate\Auth\Access\AuthorizationException();    
        }
        $count=$group->affiliates_count;
        if($count)
        {
            foreach($group->affiliates as $affiliate)
            {
                $affiliate->group_id=$data['group_id'];
                $affiliate->save();
            }
        }
        return $count;

    }
    public function setPrimaryAffLink($data,$id)
    {
        $affiliate = Affiliate::findOrFail($id);
        $affiliate->primary_aff_link = isset($data['primary_aff_link'])?$data['primary_aff_link']:null;
        $affiliate->save();
        return $affiliate;
    }
    public function setCustomAffLink($data,$id)
    {
        $affiliate = Affiliate::findOrFail($id);
        $shop=Shop::findOrFail($affiliate->shop_id);
        $result=Validator::make($data,
        [
            'path' =>[
                'required',
                function ($attribute, $value, $fail) use ($affiliate) {
                    $affiliatesCustomLinkExist = Affiliate::where([
                        'id' => $affiliate->id,
                        'affiliates_custom_link' =>'/'. $value
                    ])->get();
                    if ($affiliatesCustomLinkExist->count() != 0) {
                        $fail('Path has already been taken');

                    }
                }
            ]
            ]);
        $result->validate();
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $redirectApi=new Redirect($clientApi);
        $params=[
            "redirect"=> [
                "path"=>$data['path'],
                "target"=>$data['target']
              ]
            ];
        $redirectApi=$redirectApi->create($params);
        if(isset($redirectApi['errors']))
        {
            info($redirectApi);
            return [
                'status'=>false,
                'message'=>$redirectApi['errors']['path']['0'],
                'path'=>''
            ];
        }
        $affiliate->affiliates_custom_link = $redirectApi['redirect']['path'];
        $affiliate->id_url_redirect = $redirectApi['redirect']['id'];
        $affiliate->save();
        return[
            'status'=>true,
            'message'=>'Your redirect was created',
            'path'=>$redirectApi['redirect']['path']
        ];
    }
    public function updateCustomAffLink($data,$id)
    {
        $affiliate = Affiliate::findOrFail($id);
        $shop=Shop::findOrFail($affiliate->shop_id);
        $result=Validator::make($data,
        [
            'path' =>[
                'required',
                function ($attribute, $value, $fail) use ($affiliate) {
                    $affiliatesCustomLinkExist = Affiliate::where([
                        'id' => $affiliate->id,
                        'affiliates_custom_link' =>'/'. $value
                    ])->get();
                    if ($affiliatesCustomLinkExist->count() != 0) {
                        $fail('Path has already been taken');

                    }
                }
            ]
            ]);
        $result->validate();
        $shopName = shopNameFromDomain($shop->shop);
        $clientApi = new ClientApi($shopName, '', $shop->access_token);
        $redirectApi=new Redirect($clientApi);
        $params=[
            "redirect"=> [
                "path"=>$data['path'],
              ]
            ];
        $redirectApi=$redirectApi->update($affiliate->id_url_redirect,$params);
        if(isset($redirectApi['errors']))
        {
            return [
                'status'=>false,
                'message'=>$redirectApi['errors']['path']['0'],
                'path'=>''
            ];
        }
        $affiliate->affiliates_custom_link = $redirectApi['redirect']['path'];
        $affiliate->id_url_redirect = $redirectApi['redirect']['id'];
        $affiliate->save();
        return[
            'status'=>true,
            'message'=>'Your redirect was created',
            'path'=>$redirectApi['redirect']['path']
        ];
    }

    public function getDownlines($id)
    {
        $downlines = Affiliate::where('shop_id', auth()->user()->shop_id)->where('id', $id)->with(['children'=> function($query){
            $query->select('id','first_name','last_name','parent_id','email');
            $query->limit(200);
            $query->withCount('children');
        }])
        ->select('id','first_name','last_name','parent_id','email')
        ->first();
        return $downlines;
    }

    public function exportAffiliate($data)
    {
        $shop_id = auth()->user()->shop_id;   
        $conditions = [['affiliates.shop_id',$shop_id]];
        if(isset($data['status']))
        {
            $conditions[] = ['status',$data['status']];
        }
        if(isset($data['affiliate_id']))
        {
            $conditions[] = ['affiliates.id',$data['affiliate_id']];
        }
        if(isset($data['group_id']))
        {
            $conditions[] = ['group_id',$data['group_id']];
        }
        $affiliates = Affiliate::where($conditions);
        if(isset($data['start_date']))
        {
            $startDate = Carbon::createFromTimestamp(intval($data['start_date']))->toDateTimeString();
            $endDate = Carbon::createFromTimestamp(intval($data['end_date']))->toDateTimeString();
            $affiliates = $affiliates->whereBetween('created_at',[$startDate,$endDate]);
        }
        $affiliates = $affiliates->with(['group:id,name,slug,is_default','coupons:id,affiliate_id,code'])->get();
        $shopSettings = ShopSetting::where('shop_id',$shop_id)->select('default_affiliate_link','sub_domain')->first();
        $paymentMethodsAvailable = ShopPaymentMethod::join('payment_methods','payment_methods.id','=','shop_payment_methods.method_id')
        ->where('shop_payment_methods.shop_id',$shop_id)->select('payment_methods.*')->get();

        return[
            'affiliates' => $affiliates,
            'shopSettings' => $shopSettings,
            'paymentMethodsAvailable' => $paymentMethodsAvailable
        ] ;
    }

    public function updateProfile($data, $id)
    {
        $shopId = auth()->user()->shop_id;
        $user = Affiliate::where('shop_id',$shopId)->where('id',$id)->first(); 
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->company = $data['company'];
        $user->address = $data['address'];
        $user->facebook = $data['facebook'];
        $user->youtube = $data['youtube'];
        $user->instagram = $data['instagram'];
        $user->twitter = $data['twitter'];
        $user->website = $data['website'];
        $user->country = $data['country'];
        $user->phone = $data['phone'];
        $user->zipcode = $data['zipcode'];
        $user->city = $data['city'];
        $user->additional_infos = $data['additional_infos'];
        $user->save();
        return true;
    }

    public function updatePassword($data, $id)
    {
        $shopId = auth()->user()->shop_id;
        $user =  DB::table('affiliates')->where('shop_id',$shopId)->where('id',$id)->first(); 
        if(Hash::check($data['current_password'], $user->password))
        {
            DB::table('affiliates')->where('shop_id',$shopId)->where('id',$id)->update(['password' => Hash::make($data['password'])]);
            return $this->successResponse(null, "Password updated", 200);
        }
        else
        {
            return $this->errorResponse('Current password incorrect', 400);
        }
    }

    public function updatePaymentMethod($data, $id)
    {
        $shopId = auth()->user()->shop_id;
        Affiliate::where('shop_id', $shopId)->where('id', $id)
        ->update(['payment_method'=>$data['method'], 'payment_info'=> $data['payment_info'] ] );
        return true;
    }
}
