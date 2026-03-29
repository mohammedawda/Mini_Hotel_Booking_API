<?php

namespace Bookings;

use Illuminate\Support\ServiceProvider;
use Bookings\Contracts\BookingRepositoryInterface;
use Bookings\Infrastructure\Repositories\EloquentBookingRepository;
use Bookings\Application\Services\BookingService;
use Bookings\Domain\Services\PricingService;

class BookingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BookingRepositoryInterface::class, EloquentBookingRepository::class);
        $this->app->singleton(PricingService::class);
        $this->app->singleton(BookingService::class, function ($app) {
            return new BookingService(
                $app->make(BookingRepositoryInterface::class),
                $app->make(PricingService::class)
            );
        });
    }

    public function boot(): void
    {
        if (file_exists(__DIR__ . "/Http/routes.php")) {
            $this->loadRoutesFrom(__DIR__ . "/Http/routes.php");
        }
    }
}
