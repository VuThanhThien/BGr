<?php


namespace DoubleC\LaravelShopify\Repositories;


use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository implements Repository
{
    protected mixed $model;

    /**
     * BaseRepository constructor.
     * Set model
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * @return Model
     */
    abstract public function getModel(): string;

    /**
     * Set Model
     */
    public function setModel()
    {
        $this->model = app($this->getModel());
    }

    public function getAll(array $select = ['*']): Collection
    {
        return $this->model::all($select);
    }

    public function find(int $id): Model|null
    {
        return $this->model->find($id);
    }

    public function create(array $attributes): mixed
    {
        return $this->model->create($attributes);
    }

    public function update(int $id, array $attributes): bool
    {
        $record = $this->find($id);
        if (!$record) return false;
        return (bool)$record->update($attributes);
    }

    public function delete(int $id): bool
    {
        $record = $this->find($id);
        if (!$record) return false;
        try {
            return (bool)$record->delete();
        } catch (Exception) {
            return false;
        }
    }

    public function select($select = '*'): Builder
    {
        return $this->model->select($select);
    }

    public function findByCondition(array $condition): Builder|Model
    {
        return $this->model->where($condition)->first();
    }

    public function updateInIds(array $ids, array $attributes): int
    {

        return $this->model->whereIn('id', $ids)->update($attributes);
    }

    public function deleteInIds(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function updateOrCreate(array $maps, array $attributes): Model
    {
        return $this->model->updateOrCreate($maps, $attributes);
    }

    public function firstOrCreate(array $maps, array $attributes): Model
    {
        return $this->model->firstOrCreate($maps, $attributes);
    }

}
