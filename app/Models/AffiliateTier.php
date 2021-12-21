<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateTier extends Model
{
    use HasFactory;

    const TOTAL_CONVERSION_VALUE = 0;
    const TOTAL_EARNED_COMMISSION_VALUE = 1;
    const TOTAL_CONVERSION_ORDER_NUMBER = 2;
    const MONTHLY_SALES_AUTO_DOWNGRADE = 3;
    const MONTHLY_ORDERS_AUTO_DOWNGRADE = 4;

    protected $table = 'affiliate_tier';

    protected $casts = [
        'data_levels' => 'array'
    ];

    protected $fillable = [
        'shop_id',
        'data_levels',
        'status',
        'level_number',
        'level_condition'
    ];
}
