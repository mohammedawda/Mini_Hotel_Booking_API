<?php

namespace Bookings\Infrastructure\Repositories;

use Bookings\Contracts\BookingRepositoryInterface;
use Bookings\Infrastructure\Models\Booking;
use Bookings\Domain\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Collection;

class EloquentBookingRepository implements BookingRepositoryInterface
{
    public function all(mixed $request = null): mixed
    {
        return Booking::tableFilter($request);
    }

    public function findById(int $id): ?Booking
    {
        return Booking::find($id);
    }

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $booking = Booking::find($id);
        if (!$booking) return false;
        return $booking->update($data);
    }

    public function delete(int $id): bool
    {
        $booking = Booking::find($id);
        if (!$booking) return false;
        return $booking->delete();
    }

    public function getActiveBookingsForRoomType(int $roomTypeId, string $checkIn, string $checkOut): Collection
    {
        return Booking::where('room_type_id', $roomTypeId)
            ->whereIn('status', [BookingStatus::PENDING, BookingStatus::CONFIRMED])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                          ->where('check_out', '>=', $checkOut);
                    });
            })
            ->get();
    }
}
