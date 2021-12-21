<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionCommission extends Model
{
    use HasFactory;
    protected $table = 'collection_commission';

    public function products()
    {
        return $this->hasMany('App\Models\ProductCommission', 'collection_id', 'id');
    }
}
