<?php

namespace Bookings\Application\Services;

use Bookings\Contracts\BookingRepositoryInterface;
use Bookings\Domain\Enums\BookingStatus;
use Bookings\Infrastructure\Models\Booking;
use RoomTypes\Infrastructure\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Bookings\Domain\Services\PricingService;
use Exception;

class BookingService
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
        private PricingService $pricingService
    ) {}

    /**
     * Create a new booking with overbooking prevention.
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // 1. Pessimistic lock on the RoomType to prevent concurrent booking conflicts.
            $roomType = RoomType::where('id', $data['room_type_id'])->lockForUpdate()->first();

            if (!$roomType) {
                throw new Exception("Room Type not found.", 404);
            }

            // 2. Check availability using the pre-computed counter (safe because of the lock above).
            if ($roomType->available_rooms < $data['rooms_count']) {
                throw new Exception("Rooms are not available for the selected dates.", 401);
            }

            // 3. Verify occupancy
            if ($data['adults_count'] > ($roomType->max_occupancy * $data['rooms_count'])) {
                throw new Exception("Max occupancy exceeded for the selected room type.", 401);
            }

            // 4. Calculate total price server-side
            $data['total_price'] = $this->pricingService->calculateTotalPrice(
                (float) $roomType->base_price,
                $data['check_in'],
                $data['check_out'],
                $data['rooms_count']
            );

            // 5. Create the booking
            $booking = $this->bookingRepository->create($data);

            // 6. Atomically decrement the available_rooms counter
            $roomType->decrement('available_rooms', $data['rooms_count']);

            return $booking;
        });
    }

    public function getBookingById(int $id): ?Booking
    {
        return $this->bookingRepository->findById($id);
    }

    public function cancelBooking(int $id): bool
    {
        $booking = $this->bookingRepository->findById($id);

        if (!$booking || $booking->status === BookingStatus::CANCELLED) {
            return false;
        }

        $updated = $this->bookingRepository->update($id, ['status' => BookingStatus::CANCELLED]);

        if ($updated) {
            // Restore the rooms to the counter now that the booking is cancelled.
            RoomType::where('id', $booking->room_type_id)
                ->increment('available_rooms', $booking->rooms_count);
        }

        return $updated;
    }
}
