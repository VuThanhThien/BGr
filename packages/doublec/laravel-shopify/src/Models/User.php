<?php

namespace DoubleC\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticate;
use Illuminate\Notifications\Notifiable;

class User extends Authenticate
{
    use Notifiable;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'shop_id', 'shop_name', 'plan_name', 'password',
    ];
    /**
     * @var string[]
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return BelongsTo
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }
    
    public function setting(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\ShopSetting', 'shop_id', 'shop_id');
    }
}
