<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;
    protected $table = 'clicks';
    public $timestamps = false;
    protected $fillable = ['affiliate_id', 'count', 'date'];
    public function affiliate()
    {
        return $this->belongsTo('App\Models\Affiliate','affiliate_id','id');
    }
}
