<?php

namespace Hotels\Infrastructure\Repositories;

use Hotels\Contracts\HotelRepositoryInterface;
use Hotels\Infrastructure\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;

class EloquentHotelRepository implements HotelRepositoryInterface
{
    public function all(mixed $request = null): mixed
    {
        return Hotel::tableFilter($request);
    }

    public function findById(int $id): ?Hotel
    {
        return Hotel::find($id);
    }

    public function create(array $data): Hotel
    {
        return Hotel::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return false;
        }
        return $hotel->update($data);
    }

    public function delete(int $id): bool
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return false;
        }
        return $hotel->delete();
    }
}
