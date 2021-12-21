<?php

namespace DoubleC\LaravelShopify\Repositories\PlanRepository;

use DoubleC\LaravelShopify\Repositories\BaseRepository;
use DoubleC\LaravelShopify\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;

interface PlanRepository
{
    /**
     * Get plan have status = 1
     * @return Collection
     */
    public function getActivePlans(): Collection;
}
