<?php

namespace DoubleC\LaravelShopify\Repositories\FeatureRepository;


use DoubleC\LaravelShopify\Models\Feature;
use DoubleC\LaravelShopify\Repositories\BaseRepository;

class FeatureRepositoryEloquent extends BaseRepository implements FeatureRepository {

    public function getModel(): string
    {
        return Feature::class;
    }
}