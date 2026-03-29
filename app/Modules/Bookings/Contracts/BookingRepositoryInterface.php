<?php

namespace Bookings\Contracts;

use Bookings\Infrastructure\Models\Booking;
use Illuminate\Database\Eloquent\Collection;

interface BookingRepositoryInterface
{
    public function all(mixed $request = null): mixed;
    public function findById(int $id): ?Booking;
    public function create(array $data): Booking;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getActiveBookingsForRoomType(int $roomTypeId, string $checkIn, string $checkOut): Collection;
}
