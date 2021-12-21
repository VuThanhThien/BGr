<?php

namespace DoubleC\LaravelShopify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string title
 * @property string description
 * @property string type
 * @property string value
 * @property float usage
 * @property boolean status
 */
class Feature extends Model
{
    protected $primaryKey = 'slug';

    public $incrementing = false;

    protected $fillable = [
        'slug',
        'description',
        'type',
        'status',
    ];

    public function plans(): BelongsToMany
    {
        return $this->morphedByMany(Plan::class, 'model', 'model_has_features', 'feature', 'slug')
            ->withTimestamps();
    }

    public function shops(): BelongsToMany
    {
        return $this->morphedByMany(Shop::class, 'model', 'model_has_features', 'feature', 'slug')
            ->withTimestamps();
    }

    public function isUsage(): bool
    {
        return $this->type == 'usage';
    }

    public function isDisabled(): bool
    {
        return !$this->status;
    }

}
