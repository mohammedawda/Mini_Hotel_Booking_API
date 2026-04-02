<?php

namespace Hotels\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class RefreshHotelCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly string $city) {}

    /**
     * Clear the stale hotel cache for this city.
     * The next search request will rebuild it via Cache::remember().
     */
    public function handle(): void
    {
        Cache::forget("search_hotels_{$this->city}");
    }
}
