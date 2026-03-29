<?php

namespace Users\Contracts;

use Users\Infrastructure\Models\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
}
