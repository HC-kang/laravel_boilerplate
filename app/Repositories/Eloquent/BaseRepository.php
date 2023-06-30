<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\EloquentRepositoryInterface;

class BaseRepository implements EloquentRepositoryInterface
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById(int $id, array $columns = ['*'], array $relations = [], $isDeleted = false)
    {
        return $this->model
            ->with($relations)
            ->when($isDeleted, function ($query) {
                $query->onlyTrashed();
            })
            ->find($id, $columns);
    }

    public function all($columns = array('*'),array $relations=[], $pagination = true, $perPage = 10)
    {
        return $this->model->with($relations)->orderBy('created_at', 'desc')->get($columns);
    }

    public function allPaginated(array $relations=[], $pagination = true, $perPage = 10)
    {
        return $this->model
            ->with($relations)
            ->orderBy('id', 'desc')
            ->when($pagination, function ($query) use ($perPage) {
                return $query->paginate($perPage);
            }, function ($query) {
                return $query->get();
            });
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function createMany(array $attributes): bool
    {
        return $this->model->insert($attributes);
    }

    public function update(int $id, array $attributes):bool
    {
        $model = $this->findById($id);
        return $model->update($attributes);
    }

    public function delete(int $id):bool
    {
        $model = $this->findById($id);
        return $model->delete();
    }

    public function paginate($perPages = 10)
    {
        return $this->model->paginate($perPages);
    }
}