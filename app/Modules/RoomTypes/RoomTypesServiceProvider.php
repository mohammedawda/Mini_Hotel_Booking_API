<?php

namespace RoomTypes;

use Illuminate\Support\ServiceProvider;
use RoomTypes\Contracts\RoomTypeRepositoryInterface;
use RoomTypes\Infrastructure\Repositories\EloquentRoomTypeRepository;
use RoomTypes\Application\Services\RoomTypeService;

class RoomTypesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RoomTypeRepositoryInterface::class, EloquentRoomTypeRepository::class);
        $this->app->singleton(RoomTypeService::class, function ($app) {
            return new RoomTypeService($app->make(RoomTypeRepositoryInterface::class));
        });
    }

    public function boot(): void
    {
        if (file_exists(__DIR__ . "/Http/routes.php")) {
            $this->loadRoutesFrom(__DIR__ . "/Http/routes.php");
        }
    }
}
