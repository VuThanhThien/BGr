<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Category extends Model
{
   
    use HasFactory;

    protected $table = 'categorys';
    public function files()
    {
       return $this->hasMany('App\Models\File','category_id','id');
    }

}
