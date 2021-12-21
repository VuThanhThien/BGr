<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\ReplaceableModel;

class ProductCommission extends Model
{
    use HasFactory, ReplaceableModel;
    protected $table = 'product_commissions';
    protected $appends = ['timestamp'];

    public function collection()
    {
        return $this->belongsTo('App\Models\CollectionCommission', 'collection_id', 'id');
    }
    
    public function getTimestampAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->getTimestamp();
    }
}
