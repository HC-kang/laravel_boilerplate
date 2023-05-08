<?php

namespace App\Services;

use App\Dtos\User\CreateUserDto;
use App\Dtos\User\LoginUserDto;
use App\Dtos\User\UpdateUserDto;
use App\Repositories\UserRepositoryInterface;
use App\Resources\Strings;
use App\Services\AuthService;
use App\Traits\TokenTraits;
use Illuminate\Support\Facades\Hash;

class UserService
{
    use TokenTraits;

    private UserRepositoryInterface $userRepository;
    private AuthService $authService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthService $authService,
    ) {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function createUser(CreateUserDto $createUserDto)
    {
        try {
            $aUser = $this->userRepository->getUserByEmail($createUserDto->getEmail());
            if (!empty($aUser)) {
                throw new \Exception(Strings::USER_ALREADY_EXISTS_EXCEPTION, 99999);
            }

            $result = $this->userRepository->createUser(
                $createUserDto->getName(),
                $createUserDto->getEmail(),
                Hash::make($createUserDto->getPassword()),
                $this->generateUserToken(
                    $createUserDto->getName(),
                    $createUserDto->getEmail()
                ),
            );
            return [
                'createdUser' => $result,
            ];
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function loginUser(LoginUserDto $loginUserDto)
    {
        return $this->authService->loginUser($loginUserDto);
    }

    public function index()
    {
        try {
            return [
                'users' => $this->userRepository->all()
            ];
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function show(string $id)
    {
        try {
            return [
                'user' => $this->userRepository->findById($id)
            ];
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update(string $id, UpdateUserDto $updateUserDto)
    {
        try {
            $updateUserArray = [
                'name' => $updateUserDto->getName(),
                'email' => $updateUserDto->getEmail(),
                'password' => Hash::make($updateUserDto->getPassword()),
            ];
            return [
                'updatedUser' => $this->userRepository->update($id, $updateUserArray)
            ];
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy(string $id)
    {
        try {
            return [
                'deletedUser' => $this->userRepository->delete($id)
            ];
        } catch (\Exception $e) {
            return $e;
        }
    }
}