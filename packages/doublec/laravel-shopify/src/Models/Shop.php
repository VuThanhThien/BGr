<?php

namespace DoubleC\LaravelShopify\Models;

use Carbon\Carbon;
use DoubleC\LaravelShopify\Traits\HasFeatureTo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @mixin Model
 * @property int $id
 * @property string $shop
 * @property string $access_token
 * @property Carbon $installed_at
 * @property Carbon $activated_at
 * @property Carbon $uninstalled_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $used_days
 * @property ShopInfo $info
 * @property Collection features
 */
class Shop extends Model
{
    use HasFeatureTo;

    /**
     * @var string[]
     * Fillable
     */
    protected $fillable = [
        'shop',
        'access_token',
        'installed_at',
        'activated_at',
        'uninstalled_at',
        'cleaned_at',
        'used_days',
        'shop_inactive',
    ];
    /**
     * @var string[]
     * Cast to carbon instance
     */
    protected $dates = [
        'created_at', 'updated_at',
        'installed_at', 'activated_at', 'uninstalled_at',
        'clean_at'
    ];

    /**
     * Relationship to shop info
     * @return HasOne|ShopInfo
     */
    public function info(): HasOne|ShopInfo
    {
        return $this->hasOne(ShopInfo::class, 'shop_id', 'id');
    }

    /**
     * @return HasMany|Collection
     */
    public function subscriptions(): HasMany|Collection
    {
        return $this->hasMany(SubscriptionHistory::class, 'shop_id', 'id');
    }

    public function subscription(): Model|HasMany|null|SubscriptionHistory
    {
        return $this->subscriptions()->latest()->first();
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'shop', 'shop');
    }

    /**
     * @return HasMany | Collection
     */
    public function settings(): Collection|HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function features(): MorphToMany
    {
        return $this->morphToMany(Feature::class, 'model', 'model_has_features')->withTimestamps()->withPivot('limit');
    }

}
