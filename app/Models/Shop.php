<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DoubleC\LaravelShopify\Models\Shop as ShopD;

class Shop extends ShopD
{
    public function affiliate(){
        return $this->hasMany('App\Models\Affiliate','shop_id','id');
    }
    public function shopSetting()
    {
        return $this->hasMany('App\Models\ShopSetting','shop_id','id');
    }
}
