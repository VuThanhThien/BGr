<?php

namespace DoubleC\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property mixed expires_at
 * @property int|mixed used
 */
class SubscriptionUsage extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'subscription_id',
        'feature_slug',
        'used',
        'expires_at',
    ];
    /**
     * @var string[]
     */
    protected $dates = [
        'expires_at',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(SubscriptionHistory::class);
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class, 'feature_slug', 'slug');
    }

    public function isExpires(): bool
    {
        return now()->gt($this->expires_at);
    }

}
