<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateSetting extends Model
{
    use HasFactory;
    protected $table = 'affiliate_settings';
    protected $casts = [
        'payment_info' => 'array'
    ];
}
