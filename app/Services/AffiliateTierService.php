<?php

namespace App\Services;

use App\Models\AffiliateTier;
use App\Models\Shop;
use App\Models\ShopSetting;
use DateTime;
use DB;

class AffiliateTierService
{
    public function get()
    {
        $shopId = auth()->user()->shop_id;
        return AffiliateTier::where('shop_id',$shopId)->first();
    }

    public function updateAffiliateTier($data)
    {
        $shopId = auth()->user()->shop_id;
        AffiliateTier::updateOrCreate([
            'shop_id' => $shopId
        ],
        [
          'shop_id' => $shopId,
          'level_number' => $data['level_number'],
          'data_levels'  => $data['data_levels'],
          'status' => $data['status'],
          'level_condition' => $data['level_condition']
        ]
        );
    }

}