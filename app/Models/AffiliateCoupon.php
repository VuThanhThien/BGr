<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateCoupon extends Model
{
    use HasFactory;
    protected $table = 'affiliate_coupons';
    protected $appends = ['timestamp'];

    public function affiliate()
    {
        return $this->belongsTo('App\Models\Affiliate', 'affiliate_id', 'id');
    }

    public function getTimestampAttribute()
    {
        if(isset($this->attributes['created_at'])){
            return \Carbon\Carbon::parse($this->attributes['created_at'])->getTimestamp();
        }else{
            return '';
        }
    }
}
