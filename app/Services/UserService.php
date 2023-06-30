<?php

namespace App\Services;

use App\Dtos\User\CreateUserDto;
use App\Dtos\User\LoginUserDto;
use App\Dtos\User\UpdateUserDto;
use App\Repositories\UserRepositoryInterface;
use App\Resources\Cons;
use App\Resources\Strings;
use App\Services\AuthService;
use App\Traits\TokenTraits;
use Illuminate\Support\Facades\Hash;
use Exception;

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
                throw new Exception(Strings::USER_ALREADY_EXISTS_EXCEPTION, 99999);
            }

            $result = $this->userRepository->createUser(
                $createUserDto->getName(),
                $createUserDto->getEmail(),
                Hash::make($createUserDto->getPassword()),
                $createUserDto->getRole(),
                $this->generateUserToken(
                    $createUserDto->getName(),
                    $createUserDto->getEmail()
                ),
            );
            return [
                'createdUser' => $result,
            ];
        } catch (Exception $e) {
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
                'users' => $this->userRepository->allPaginated()
            ];
        } catch (Exception $e) {
            return $e;
        }
    }

    public function show(int $id)
    {
        try {
            return [
                'user' => $this->userRepository->findById($id)
            ];
        } catch (Exception $e) {
            return $e;
        }
    }

    public function update(int $id, UpdateUserDto $updateUserDto)
    {
        try {
            $user = $this->userRepository->findById($id);
            if (empty($user)) {
                throw new Exception(Strings::USER_NOT_FOUND_EXCEPTION, 99999);
            }
            $updateUserArray = [
                'name' => $updateUserDto->getName(),
                'password' => Hash::make($updateUserDto->getPassword()),
                'role' => $updateUserDto->getRole(),
            ];
            $user->update($updateUserArray);
            $user->save();
            $user->refresh();
            return [
                'updatedUser' => $user,
            ];
        } catch (Exception $e) {
            return $e;
        }
    }

    public function destroy(int $id)
    {
        try {
            $user = $this->userRepository->findById($id);
            if ($user->role === Cons::ROLE_SUPER_ADMIN) {
                throw new Exception(Strings::SUPER_ADMIN_DELETE_EXCEPTION, 99999);
            }
            if (empty($user)) {
                throw new Exception(Strings::USER_NOT_FOUND_EXCEPTION, 99999);
            }
            $user->delete();
            return [
                'deletedUser' => $user,
            ];
        } catch (Exception $e) {
            return $e;
        }
    }

    public function me(int $id)
    {
        try {
            return [
                'user' => $this->userRepository->findById($id)
            ];
        } catch (Exception $e) {
            return $e;
        }
    }
}
