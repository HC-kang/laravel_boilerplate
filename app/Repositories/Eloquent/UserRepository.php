<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\UserRepositoryInterface;
use App\Traits\ApiResponseTraits;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    use ApiResponseTraits;

    protected $model;

    public function __construct(
        User $model,
    ) {
        parent::__construct($model);
        $this->model = $model;
    }

    public function getUserByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function createUser(string $name, string $email, string $hashedPassword, int $role, string $token)
    {
        $commonUser = new User();
        $commonUser->name = $name;
        $commonUser->email = $email;
        $commonUser->password = $hashedPassword;
        $commonUser->role = $role;
        $commonUser->remember_token = $token;
        $commonUser->save();
        return $commonUser;
    }
}