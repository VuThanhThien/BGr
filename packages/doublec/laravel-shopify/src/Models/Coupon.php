<?php

namespace DoubleC\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string code
 */
class Coupon extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'code',
        'discount_id',
        'shop',
        'times_used',
        'status'
    ];

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }
}
