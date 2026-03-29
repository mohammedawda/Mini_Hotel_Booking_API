<?php

namespace RoomTypes\Application\Services;

use RoomTypes\Contracts\RoomTypeRepositoryInterface;
use RoomTypes\Infrastructure\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;

class RoomTypeService
{
    public function __construct(
        private RoomTypeRepositoryInterface $roomTypeRepository
    ) {}

    public function getAllRoomTypes(): Collection
    {
        return $this->roomTypeRepository->all();
    }

    public function getRoomTypeById(int $id): ?RoomType
    {
        return $this->roomTypeRepository->findById($id);
    }

    public function createRoomType(array $data): RoomType
    {
        return $this->roomTypeRepository->create($data);
    }

    public function updateRoomType(int $id, array $data): bool
    {
        return $this->roomTypeRepository->update($id, $data);
    }

    public function deleteRoomType(int $id): bool
    {
        return $this->roomTypeRepository->delete($id);
    }
}
