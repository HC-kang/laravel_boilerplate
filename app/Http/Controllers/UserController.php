<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use App\Traits\ApiResponseTraits;
use App\Traits\TokenTraits;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponseTraits, TokenTraits;

    private UserService $userService;

    public function __construct(
        UserService $userService,
    ) {
        $this->userService = $userService;
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $createUserDto = $request->toCreateUserDto();
        $result = $this->userService->createUser($createUserDto);
        return $this->successResponse($result);
    }

    public function loginUser(LoginUserRequest $request): JsonResponse
    {
        $loginUserDto = $request->toLoginUserDto();
        $result = $this->userService->loginUser($loginUserDto);
        if ($result instanceof \Exception) {
            return $this->errorResponse($result);
        }
        return $this->respondWithToken($result);
    }

    public function index(): JsonResponse
    {
        $result = $this->userService->index();
        return $this->successResponse($result);
    }

    public function show(string $id): JsonResponse
    {
        $result = $this->userService->show($id);
        return $this->successResponse($result);
    }

    public function update(string $id, UpdateUserRequest $updateUserRequest): JsonResponse
    {
        $updateUserDto = $updateUserRequest->toUpdateUserDto();
        $result = $this->userService->update($id, $updateUserDto);
        return $this->successResponse($result);
    }

    public function destroy(string $id): JsonResponse
    {
        $result = $this->userService->destroy($id);
        return $this->successResponse($result);
    }
}