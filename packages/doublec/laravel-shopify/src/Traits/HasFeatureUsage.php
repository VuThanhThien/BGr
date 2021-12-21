<?php

namespace DoubleC\LaravelShopify\Traits;

use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;
use DoubleC\LaravelShopify\Models\SubscriptionUsage;
use Illuminate\Database\Eloquent\Builder;

trait HasFeatureUsage
{

    public function registerFeatureQuota(string $feature, int $expiresAfter = 30)
    {
        /** @var SubscriptionHistory $this */
        return $this->usages()->attach([
            $feature => [
                'expires_at' => now()->addDays($expiresAfter)
            ]
        ]);
    }

    public function resetQuota(string $feature)
    {

        $usage = $this->hasUsage($feature);

        if ($usage) {
            $usage->used = 0;
            $usage->expires_at = now()->addDays(30);
            $usage->save();
        }

    }

    public function hasUsage(string $feature): SubscriptionUsage|null
    {
        /** @var Builder $this */
        $subscription = $this
            ->whereHas('usages', function ($query) use ($feature) {
                $query->where('feature_slug', $feature);
            })
            ->with('usages')
            ->latest()
            ->first();

        return $subscription ? $subscription->usages->first() : null;
    }

    public function limit(string $feature): int
    {
        /** @var Shop $shop */
        $shop = $this->shop;

        $featureFind = $shop->getAllFeatures()->where('slug', $feature)->first();

        return $featureFind ? $featureFind->pivot->limit : 0;
    }

    public function consumed(string $feature)
    {
        $usage = $this->hasUsage($feature);

        return $usage ? $usage->used : 0;
    }

    public function remaining(string $feature)
    {
        return $this->limit($feature) - $this->consumed($feature);
    }

}