<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
   
    use HasFactory;

    protected $table = 'files';
    protected $appends = ['image'];

    public function getImageAttribute(){
        if(isset($this->attributes['path_name']))
        {
            return  'https://d2xrtfsb9f45pw.cloudfront.net/'.$this->attributes['path_name'];
        }
        else
        return null;

    }

}
