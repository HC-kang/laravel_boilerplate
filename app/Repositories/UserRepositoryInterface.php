<?php

namespace App\Repositories;

use App\Repositories\EloquentRepositoryInterface;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    public function getUserByEmail(string $email);
    public function createUser(string $name, string $email, string $hashedPassword, int $role, string $token);
}