<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function model();

    public function with(array $relations);

    public function find(int $id): Model;

    public function create(array $attributes): Model;

    public function update(Model $model, array $attributes);

    public function delete(Model $model);
}
