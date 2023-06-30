<?php

namespace App\Dtos\User;
use App\Dtos\BaseDto;

class UpdateUserDto extends BaseDto
{
    private ?string $name;
    private ?string $email;
    private ?string $password;
    private ?int $role;

    public function __construct(
        ?string $name,
        ?string $email,
        ?string $password,
        ?int $role,
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRole(): ?int
    {
        return $this->role;
    }
}