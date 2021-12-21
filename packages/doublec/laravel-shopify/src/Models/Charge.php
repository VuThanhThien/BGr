<?php

namespace DoubleC\LaravelShopify\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed starts_at
 * @property Carbon|mixed cancelled_on
 * @property mixed billing_on
 * @property int id
 */
class Charge extends Model
{

    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string[]
     */
    protected $fillable = [
        'charge_id',
        'shop_id',
        'name',
        'price',
        'trial_days',
        'type',
        'status',
        'billing_on',
        'created_at',
        'updated_at',
        'trial_ends_on',
        'cancelled_on',
        'test',
        'description',
    ];
    /**
     * @var string[]
     */
    protected $dates = [
        'created_at', 'updated_at', 'billing_on', 'trial_ends_on', 'cancelled_on',
    ];

    protected $casts = [
        'description' => 'array'
    ];

    /**
     * Check is canceled charge
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->attributes['cancelled_on'] !== null;
    }
}
