<?php

namespace App\Dtos\User;
use App\Dtos\BaseDto;
use App\Resources\Cons;

class CreateUserDto extends BaseDto
{
    private string $name;
    private string $email;
    private string $password;
    private ?int $role;

    public function __construct(
        string $name,
        string $email,
        string $password,
        ?int $role,
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role ?? Cons::ROLE_ADMIN;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): int
    {
        return $this->role;
    }
}