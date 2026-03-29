<?php

namespace Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Users\Application\DTOs\LoginDTO;
use Users\Application\DTOs\RegisterDTO;
use Users\Contracts\AuthServiceInterface;
use Users\Http\Requests\LoginRequest;
use Users\Http\Requests\RegisterRequest;
use Users\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    public function register(RegisterRequest $request)
    {
        $dto = RegisterDTO::fromRequest($request->validated());
        $result = $this->authService->register($dto);

        return response()->json([
            "user" => new UserResource($result["user"]),
            "token" => $result["token"],
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $dto = LoginDTO::fromRequest($request->validated());
        $result = $this->authService->login($dto);

        return response()->json([
            "user" => new UserResource($result["user"]),
            "token" => $result["token"],
        ]);
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json(["message" => "Logged out successfully"]);
    }
}
