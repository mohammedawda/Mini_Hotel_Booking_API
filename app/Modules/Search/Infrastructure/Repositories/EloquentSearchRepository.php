<?php

namespace Search\Infrastructure\Repositories;

use Search\Contracts\SearchRepositoryInterface;
use Hotels\Infrastructure\Models\Hotel;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Bookings\Domain\Services\PricingService;

class EloquentSearchRepository implements SearchRepositoryInterface
{
    public function __construct(
        private PricingService $pricingService
    ) {}

    public function getAvailableHotels(array $filters): Collection
    {
        $city = $filters['city'];
        $checkIn = $filters['check_in'];
        $checkOut = $filters['check_out'];
        $adultsCount = $filters['adults_count'];

        // Use tableFilter to handle city and status filtering
        $hotels = Cache::remember("search_hotels_{$city}", 3600, function () {
            return Hotel::with(['roomTypes' => function ($query) {
                    $query->where('status', 'active');
                }])
                ->tableFilter(request())
                ->get();
        });

        $results = collect();

        foreach ($hotels as $hotel) {
            foreach ($hotel->roomTypes as $roomType) {
                // Check occupancy
                if ($roomType->max_occupancy < $adultsCount) {
                    continue;
                }

                // Check availability from the pre-computed counter
                $availableRooms = $roomType->available_rooms;

                if ($availableRooms > 0) {
                    $totalPrice = $this->pricingService->calculateTotalPrice(
                        (float) $roomType->base_price,
                        $checkIn,
                        $checkOut
                    );

                    $results->push([
                        'hotel'           => $hotel,
                        'room_type'       => $roomType,
                        'available_rooms' => $availableRooms,
                        'total_price'     => $totalPrice,
                    ]);
                }
            }
        }

        return $results;
    }
}
