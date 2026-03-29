<?php

namespace Users\Application\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Users\Application\DTOs\LoginDTO;
use Users\Application\DTOs\RegisterDTO;
use Users\Contracts\AuthServiceInterface;
use Users\Contracts\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(RegisterDTO $dto): array
    {
        $user = $this->userRepository->create([
            "name" => $dto->name,
            "email" => $dto->email,
            "password" => Hash::make($dto->password),
        ]);

        $token = $user->createToken("auth_token")->plainTextToken;

        return [
            "user" => $user,
            "token" => $token,
        ];
    }

    public function login(LoginDTO $dto): array
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                "email" => ["Invalid credentials."],
            ]);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return [
            "user" => $user,
            "token" => $token,
        ];
    }

    public function logout(): void
    {
        auth()->user()->tokens()->delete();
    }
}
