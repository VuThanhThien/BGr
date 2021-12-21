<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $table = 'programs';

    public function affiliates()
    {
        return $this->hasMany('App\Models\Affiliate', 'program_id', 'id');
    }
}
