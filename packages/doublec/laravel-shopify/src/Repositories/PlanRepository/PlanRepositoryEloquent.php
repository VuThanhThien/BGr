<?php

namespace DoubleC\LaravelShopify\Repositories\PlanRepository;

use DoubleC\LaravelShopify\Models\Plan;
use DoubleC\LaravelShopify\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class PlanRepositoryEloquent extends BaseRepository implements PlanRepository
{

    /**
     * @inheritDoc
     */
    public function getModel(): string
    {
        return Plan::class;
    }

    public function getActivePlans(): Collection
    {
        return $this->model->whereStatus(true)->get();
    }
}
