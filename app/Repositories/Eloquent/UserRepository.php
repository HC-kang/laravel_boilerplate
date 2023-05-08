<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\UserRepositoryInterface;
use App\Traits\ApiResponseTraits;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    use ApiResponseTraits;

    protected $userModel;

    public function __construct(
        User $userModel,
    ) {
        parent::__construct($userModel);
        $this->userModel = $userModel;
    }

    public function getUserByEmail(string $email)
    {
        return $this->userModel->where('email', $email)->first();
    }

    public function createUser(string $name, string $email, string $hashedPassword, string $token)
    {
        $commonUser = new User();
        $commonUser->name = $name;
        $commonUser->email = $email;
        $commonUser->password = $hashedPassword;
        $commonUser->remember_token = $token;
        $commonUser->save();
        return $commonUser;
    }
}