<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyGdprHistory extends Model
{
    use HasFactory;
    protected $casts = [
        'data' => 'array'
    ];
    protected $table = 'shopify_gdpr_history';

    
}
