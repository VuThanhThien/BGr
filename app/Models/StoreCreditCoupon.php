<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCreditCoupon extends Model
{
    use HasFactory;

    protected $table = 'store_credit_coupons';
    protected $appends = ['timestamp'];
    protected $fillable = [
        'code',
        'description',
        'affiliate_id',
        'shop_id' ,
        'discount_type',
        'discount_amount'
    ];

    public function getTimestampAttribute()
    {
        if(isset($this->attributes['created_at']))
        {
            return Carbon::parse($this->attributes['created_at'])->getTimestamp();
        }
        else
        {
            return '';
        }
    }
}
