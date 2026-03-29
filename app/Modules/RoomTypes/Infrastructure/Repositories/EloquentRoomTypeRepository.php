<?php

namespace RoomTypes\Infrastructure\Repositories;

use RoomTypes\Contracts\RoomTypeRepositoryInterface;
use RoomTypes\Infrastructure\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;

class EloquentRoomTypeRepository implements RoomTypeRepositoryInterface
{
    public function all(mixed $request = null): mixed
    {
        return RoomType::tableFilter($request);
    }

    public function findById(int $id): ?RoomType
    {
        return RoomType::find($id);
    }

    public function create(array $data): RoomType
    {
        return RoomType::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $roomType = RoomType::find($id);
        if (!$roomType) {
            return false;
        }
        return $roomType->update($data);
    }

    public function delete(int $id): bool
    {
        $roomType = RoomType::find($id);
        if (!$roomType) {
            return false;
        }
        return $roomType->delete();
    }
}
