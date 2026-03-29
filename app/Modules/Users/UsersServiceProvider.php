<?php

namespace Users;

use Illuminate\Support\ServiceProvider;
use Users\Contracts\AuthServiceInterface;
use Users\Contracts\UserRepositoryInterface;
use Users\Infrastructure\Repositories\EloquentUserRepository;
use Users\Application\Services\AuthService;

class UsersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    public function boot(): void
    {
        if (file_exists(__DIR__ . "/Http/routes.php")) {
            $this->loadRoutesFrom(__DIR__ . "/Http/routes.php");
        }
    }
}
