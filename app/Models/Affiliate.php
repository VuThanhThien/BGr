<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
class Affiliate extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;
    protected $table = 'affiliates';
    protected $appends = ['full_name', 'timestamp'];
    protected $casts = [
        'payment_info' => 'array',
        'additional_infos' => 'array'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'email',
        'password',
        'shop_id',
        'group_id',
        'is_active',
        'first_name',
        'last_name',
        'company',
        'address',
        'zipcode',
        'city',
        'state',
        'country',
        'phone',
        "is_active",
        "ga",
        "hash_code",
        "personal_detail",
        "website",
        "parent_id",
        "facebook",
        "youtube",
        "instagram",
        "twitter",
        "additional_info",
        "verified",
        "login_count",
        "postback_url",
        "is_pending",
        "fb_pixel",
        "payment_method",
        "payment_info"
    ];
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop','shop_id','id');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\Group', 'group_id', 'id');
    }

    public function setting()
    {
        return $this->hasOne('App\Models\AffiliateSetting', 'affiliate_id', 'id');
    }

    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }

    public function clicks()
    {
        return $this->hasMany('App\Models\Click', 'affiliate_id', 'id');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Affiliate', 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Affiliate', 'parent_id', 'id');
    }

    public function coupons()
    {
        return $this->hasMany('App\Models\AffiliateCoupon', 'affiliate_id', 'id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getTimestampAttribute()
    {
        if(isset($this->attributes['created_at'])){
            return \Carbon\Carbon::parse($this->attributes['created_at'])->getTimestamp();
        }else{
            return '';
        }
    }

    public function getLastLoginAttribute()
    {
        if(isset($this->attributes['last_login'])){
            return \Carbon\Carbon::parse($this->attributes['last_login'])->getTimestamp();
        }else{
            return null;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

}
