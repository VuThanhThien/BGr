<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Group extends Model
{
    use HasFactory, Sluggable;

    const PERCENT_OF_SALE = 1;
    const FLAT_RATE_PER_ORDER = 2;
    const FLAT_RATE_PER_ITEM = 3;

    protected $fillable = ['id', 'name', 'description', 'is_active', 'is_default', 'is_private'];
    protected $table = 'groups';
    protected $casts = [
        'mlm_tree' => 'array',
        'registration_form' => 'array',
        'payment_methods' => 'array'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    public function affiliates()
    {
        return $this->hasMany('App\Models\Affiliate', 'group_id', 'id');
    }

    public function getRegistrationFormAttribute()
    {
        if($this->attributes['registration_form']) {
            return json_decode($this->attributes['registration_form']);
            // return array_merge_recursive(config('myconfig.registration'), json_decode($this->attributes['registration_form'], true));
        }else{
            return config('myconfig.registration');
        }

    }
}
