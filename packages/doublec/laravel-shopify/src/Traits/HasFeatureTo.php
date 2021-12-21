<?php

namespace DoubleC\LaravelShopify\Traits;

use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Models\Shop;
use DoubleC\LaravelShopify\Models\SubscriptionHistory;
use DoubleC\LaravelShopify\Repositories\FeatureRepository\FeatureRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait HasFeatureTo
{
    /**
     * Get all feature of shop
     * @return Collection
     */
    public function getAllFeatures(): Collection
    {
        return $this->getDirectFeatures()->merge($this->getFeaturesViaPlan());
    }

    /**
     * @return Collection
     */
    public function getDirectFeatures(): Collection
    {
        return $this->features;
    }

    /**
     * @return array|Collection
     */
    public function getFeaturesViaPlan(): array|Collection
    {
        /** @var SubscriptionHistory $subscription */
        $subscription = $this->subscriptions()->latest()->first();

        if ($subscription) {
            /** @var Plan $plan */
            $plan = $subscription->plan()->first();
            return $plan->features;
        }

        return [];
    }

    /**
     * @param array $features
     * @return array
     */
    public function giveDirectFeatures(array $features): array
    {
        return $this->features()->sync($features);
    }

    public function can(string $feature): bool
    {
        return $this->getAllFeatures()->contains($feature);
    }

    public function hasDirectFeature(string $feature): bool
    {
        return $this->getDirectFeatures()->contains($feature);
    }

    public function hasPlanFeature(string $feature): bool
    {
        return $this->getFeaturesViaPlan()->contains($feature);
    }

    public function removeDirectFeatures(array $features): int
    {
        return $this->features()->detach($features);
    }


}