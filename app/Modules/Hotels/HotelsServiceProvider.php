<?php

namespace Hotels;

use Illuminate\Support\ServiceProvider;
use Hotels\Contracts\HotelRepositoryInterface;
use Hotels\Infrastructure\Repositories\EloquentHotelRepository;
use Hotels\Application\Services\HotelService;
use Hotels\Infrastructure\Models\Hotel;
use Hotels\Infrastructure\Observers\HotelObserver;

class HotelsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HotelRepositoryInterface::class, EloquentHotelRepository::class);
        $this->app->singleton(HotelService::class, function ($app) {
            return new HotelService($app->make(HotelRepositoryInterface::class));
        });
    }

    public function boot(): void
    {
        if (file_exists(__DIR__ . "/Http/routes.php")) {
            $this->loadRoutesFrom(__DIR__ . "/Http/routes.php");
        }

        Hotel::observe(HotelObserver::class);
    }
}
