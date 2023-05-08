<?php

namespace App\Services;

use App\Dtos\User\LoginUserDto;
use App\Resources\Strings;


class AuthService
{
    public function loginUser(LoginUserDto $userLoginDto)
    {
        try {
            $credentials = [
                'email' => $userLoginDto->getEmail(),
                'password' => $userLoginDto->getPassword(),
            ];

            if (!auth()->attempt($credentials)) {
                throw new \Exception(Strings::USER_LOGIN_FAILED_EXCEPTION, 99999);
            }

            return auth()->user();

        } catch (\Exception $e) {
            return $e;
        }
    }
}