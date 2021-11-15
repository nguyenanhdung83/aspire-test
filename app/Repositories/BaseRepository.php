<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function model()
    {
        return $this->model;
    }

    public function with(array $relations)
    {
        return $this->model()->with($relations);
    }

    public function find(int $id): Model
    {
        return $this->model()->find($id);
    }

    public function create(array $attributes): Model
    {
        return $this->model()->create($attributes);
    }

    public function update(Model $model, array $attributes): void
    {
        $model->update($attributes);
    }

    public function delete(Model $model)
    {
        $model->delete();
    }
}
