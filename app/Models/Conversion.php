<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Conversion extends Model
{
    const TRACKING_TYPE_LINK = 1;
    const TRACKING_TYPE_COUPON = 2;
    const TRACKING_TYPE_NETWORK = 3;
    const RECRUITMENT_BONUS = 4;
    const ADD_MANUAL = 5;
    
    use HasFactory;

    protected $table = 'conversions';
    protected $casts = [
        'customer_info' => 'array',
        'commission_explanation' => 'array'
    ];
    
    protected $appends = ['timestamp'];

    public function affiliate()
    {
        return $this->belongsTo('App\Models\Affiliate', 'affiliate_id', 'id');
    }

    public function network()
    {
        return $this->hasMany('App\Models\Conversion', 'conversion_id', 'id');
    }

    public function getTimestampAttribute()
    {
        if(isset($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->getTimestamp();
        }else{
            return '';
        }
    }
}
