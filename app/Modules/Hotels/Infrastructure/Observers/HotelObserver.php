<?php

namespace Hotels\Infrastructure\Observers;

use Hotels\Application\Jobs\RefreshHotelCacheJob;
use Hotels\Infrastructure\Models\Hotel;

class HotelObserver
{
    /**
     * Dispatched on create so a newly-active hotel's city cache is cleared
     * and the hotel shows up in the next search request.
     */
    public function created(Hotel $hotel): void
    {
        RefreshHotelCacheJob::dispatch($hotel->city);
    }

    /**
     * Dispatched on update (e.g. status change, city rename) so stale data
     * is evicted transparently in the background.
     */
    public function updated(Hotel $hotel): void
    {
        RefreshHotelCacheJob::dispatch($hotel->city);

        // If the city was changed, also clear the old city's cache.
        if ($hotel->wasChanged('city')) {
            RefreshHotelCacheJob::dispatch($hotel->getOriginal('city'));
        }
    }

    /**
     * Dispatched on delete so the hotel no longer appears in search results.
     */
    public function deleted(Hotel $hotel): void
    {
        RefreshHotelCacheJob::dispatch($hotel->city);
    }
}
