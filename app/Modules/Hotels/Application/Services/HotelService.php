<?php

namespace Hotels\Application\Services;

use Hotels\Contracts\HotelRepositoryInterface;
use Hotels\Infrastructure\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;

class HotelService
{
    public function __construct(
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function getAllHotels(mixed $request = null): mixed
    {
        return $this->hotelRepository->all($request);
    }

    public function getHotelById(int $id): ?Hotel
    {
        return $this->hotelRepository->findById($id);
    }

    public function createHotel(array $data): Hotel
    {
        return $this->hotelRepository->create($data);
    }

    public function updateHotel(int $id, array $data): bool
    {
        return $this->hotelRepository->update($id, $data);
    }

    public function deleteHotel(int $id): bool
    {
        return $this->hotelRepository->delete($id);
    }
}
