<?php

namespace DoubleC\LaravelShopify\Models;

use DoubleC\LaravelShopify\Traits\HasFeatureUsage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property mixed starts_at
 * @property Shop shop
 */
class SubscriptionHistory extends Model
{
    use HasFeatureUsage;

    /**
     * @var string[]
     */
    protected $fillable = [
        'shop_id',
        'plan_id',
        'coupon_code',
        'charge_id',
        'name',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancels_at',
    ];
    /**
     * @var string[]
     */
    protected $dates = [
        'cancels_at', 'trial_ends_at', 'ends_at', 'starts_at',
    ];

    /**
     * Check is canceled charge
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->attributes['cancels_at'] !== null;
    }

    /**
     * Return charge of subscription
     * @return BelongsTo|Charge
     */
    public function charge(): BelongsTo|Charge
    {
        return $this->belongsTo(Charge::class);
    }

    /**
     * Check us charged for this subscription
     * @return bool
     */
    public function isCharged(): bool
    {
        return $this->attributes['charge_id'] !== null;
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(SubscriptionUsage::class, 'subscription_id', 'id')->latest();
    }

}
