<?php

namespace App\Services\Affiliate;

use App\Models\Affiliate;
use App\Models\File;
use App\Models\Group;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

Class CategoryService
{
    public function getFileCategory($data,$id)
    {
        $user=auth('affiliate-api')->user();
        $groupId=$user->group_id;
        $shopId=$user->shop_id;
        $conditions=[['shop_id',$shopId]];
        if(isset($data['name']) && !empty($data['name']))
        {
            $conditions[]=['name','like','%'.$data['name'].'%'];
        }
        if(isset($data['type']))
        {
            $conditions[]=['type',$data['type']];
        }
        $files=File::where($conditions)->where(function($query) use($groupId){
            $query->where('group_id',$groupId)->orWhere('group_id',0);
        })->paginate(9);
        return $files;
    }
    
}
