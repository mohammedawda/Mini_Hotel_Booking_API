<?php

namespace Users\Contracts;

use Users\Application\DTOs\LoginDTO;
use Users\Application\DTOs\RegisterDTO;

interface AuthServiceInterface
{
    public function register(RegisterDTO $dto): array;
    public function login(LoginDTO $dto): array;
    public function logout(): void;
}
