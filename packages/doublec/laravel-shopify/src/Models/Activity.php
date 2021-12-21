<?php

namespace DoubleC\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'log_name',
        'description',
        'properties'
    ];
}
