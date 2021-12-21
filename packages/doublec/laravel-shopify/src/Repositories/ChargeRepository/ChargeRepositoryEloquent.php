<?php


namespace DoubleC\LaravelShopify\Repositories\ChargeRepository;


use DoubleC\LaravelShopify\Models\Charge;
use DoubleC\LaravelShopify\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ChargeRepositoryEloquent extends BaseRepository implements ChargeRepository
{

    public function getModel(): string
    {
        return Charge::class;
    }
}
