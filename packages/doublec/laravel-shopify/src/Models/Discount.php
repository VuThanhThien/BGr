<?php

namespace DoubleC\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property double value
 * @property int id
 * @property int usage_limit
 * @property string type
 * @property string code
 */
class Discount extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'type',
        'value',
        'usage_limit',
        'trial_days',
        'started_at',
        'expired_at',
        'plan_id'
    ];
    /**
     * @var string[]
     */
    protected $dates = [
        'started_at', 'expired_at',
    ];

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class, 'discount_id', 'id');
    }

}
