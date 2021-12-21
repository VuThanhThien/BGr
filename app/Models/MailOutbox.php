<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailOutbox extends Model
{
    use HasFactory;

    protected $table = 'mail_outbox';
    protected $appends = ['timestamp'];

    public function getTimestampAttribute()
    {
        if(isset($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->getTimestamp();
        }
        else{
            return '';
        }
    }
    
    public function affiliate(){
      return  $this->belongsTo('App\Models\Affiliate','affiliate_id','id');
    }
}
