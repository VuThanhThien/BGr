<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;
    protected $table = 'tracking';
    public $timestamps = false;
    protected $fillable = ['client_id', 'cart_token', 'checkout_token', 'shop_id', 'affiliate_id', 'created_at', 'expire_at'];


    public function affiliate()
    {
        return $this->belongsTo('App\Models\Affiliate', 'affiliate_id', 'id');
    }
}
