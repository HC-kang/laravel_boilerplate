<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface EloquentRepositoryInterface
{
    public function findById(int $id, array $columns = ['*'], array $relations = [], $isDeleted = false);

    public function all(array $columns = ['*'],array $relations=[]);

    public function allPaginated(array $relations = [], $pagination = true, $perPage = 10);

    public function create(array $payload): Model;

    public function update(int $id , array $payload): bool;

    public function delete(int $id): bool;

    public function paginate(int $perPages);
}