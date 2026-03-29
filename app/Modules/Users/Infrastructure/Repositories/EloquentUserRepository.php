<?php

namespace Users\Infrastructure\Repositories;

use Users\Contracts\UserRepositoryInterface;
use Users\Infrastructure\Models\User;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where("email", $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }
}
