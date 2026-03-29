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
            // 1. Pessimistic lock on the RoomType to prevent concurrent check conflicts
            $roomType = RoomType::where('id', $data['room_type_id'])->lockForUpdate()->first();

            if (!$roomType) {
                throw new Exception("Room Type not found.");
            }

            // 2. Calculate available rooms for the requested dates
            $activeBookings = $this->bookingRepository->getActiveBookingsForRoomType(
                $data['room_type_id'],
                $data['check_in'],
                $data['check_out']
            );

            $bookedRoomsCount = $activeBookings->sum('rooms_count');
            $availableRooms = $roomType->total_rooms - $bookedRoomsCount;

            if ($availableRooms < $data['rooms_count']) {
                throw new Exception("Rooms are not available for the selected dates.");
            }

            // 3. Verify occupancy
            if ($data['adults_count'] > ($roomType->max_occupancy * $data['rooms_count'])) {
                throw new Exception("Max occupancy exceeded for the selected room type.");
            }

            // 4. Calculate total price server-side
            $data['total_price'] = $this->pricingService->calculateTotalPrice(
                (float) $roomType->base_price,
                $data['check_in'],
                $data['check_out'],
                $data['rooms_count']
            );

            // 5. Create the booking
            return $this->bookingRepository->create($data);
        });
    }

    public function getBookingById(int $id): ?Booking
    {
        return $this->bookingRepository->findById($id);
    }

    public function cancelBooking(int $id): bool
    {
        return $this->bookingRepository->update($id, ['status' => BookingStatus::CANCELLED]);
    }
}
