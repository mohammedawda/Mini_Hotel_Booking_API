<?php

namespace Bookings\Infrastructure\Repositories;

use Bookings\Contracts\BookingRepositoryInterface;
use Bookings\Infrastructure\Models\Booking;
use Bookings\Domain\Enums\BookingStatus;

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
}
