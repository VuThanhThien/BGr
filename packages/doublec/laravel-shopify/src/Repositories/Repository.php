<?php


namespace DoubleC\LaravelShopify\Repositories;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

interface Repository
{
    /**
     * @param array $select
     * @return Collection
     */
    public function getAll(array $select = ['*']): Collection;

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): Model|null;

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes): mixed;

    /**
     * @param int $id
     * @param array $attributes
     * @return bool
     */
    public function update(int $id, array $attributes): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param string $select
     * @return Builder
     */
    public function select($select = '*') : Builder;

    /**
     * @param array $condition
     * @return Builder|Model
     */
    public function findByCondition(array $condition): Builder|Model;

    /**
     * @param array $ids
     * @param array $attributes
     * @return int
     */
    public function updateInIds(array $ids, array $attributes): int;

    /**
     * @param array $ids
     * @return int
     */
    public function deleteInIds(array $ids): int;

    /**
     * @param array $maps
     * @param array $attributes
     * @return Model
     */
    public function updateOrCreate(array $maps, array $attributes): Model;

    /**
     * @param array $maps
     * @param array $attributes
     * @return Model
     */
    public function firstOrCreate(array $maps, array $attributes): Model;
}
