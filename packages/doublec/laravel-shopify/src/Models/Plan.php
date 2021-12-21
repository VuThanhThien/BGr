<?php

namespace DoubleC\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property double price
 * @property int id
 * @property string name
 * @property int trial_days
 * @property Collection features
 */
class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'trial_days',
        'type',
        'status',
    ];

    /**
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(SubscriptionHistory::class, 'plan_id', 'id');
    }

    /**
     * @return bool
     */
    public function isFree(): bool
    {
        return $this->price <= 0;
    }

    public function features(): MorphToMany
    {
        return $this->morphToMany(Feature::class, 'model', 'model_has_features')->withTimestamps()->withPivot('limit');
    }

}
