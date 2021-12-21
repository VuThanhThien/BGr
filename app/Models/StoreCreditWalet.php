<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCreditWalet extends Model
{
    use HasFactory;

    protected $table = 'store_credit_walets';
    protected $fillable = [
        'affiliate_id',
        'shop_id',
        'description',
        'total' 
    ];
}
