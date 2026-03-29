<?php

namespace Search;

use Illuminate\Support\ServiceProvider;
use Search\Application\Services\SearchService;
use Search\Contracts\SearchRepositoryInterface;
use Search\Infrastructure\Repositories\EloquentSearchRepository;
use Bookings\Contracts\BookingRepositoryInterface;
use Bookings\Domain\Services\PricingService;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SearchRepositoryInterface::class, function ($app) {
            return new EloquentSearchRepository(
                $app->make(BookingRepositoryInterface::class),
                $app->make(PricingService::class)
            );
        });

        $this->app->singleton(SearchService::class, function ($app) {
            return new SearchService($app->make(SearchRepositoryInterface::class));
        });
    }

    public function boot(): void
    {
        if (file_exists(__DIR__ . "/Http/routes.php")) {
            $this->loadRoutesFrom(__DIR__ . "/Http/routes.php");
        }
    }
}
