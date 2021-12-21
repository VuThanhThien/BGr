<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'shop_payment_methods';
    public $timestamps = false;
    protected $fillable = ['shop_id', 'method_id'];
    
}
