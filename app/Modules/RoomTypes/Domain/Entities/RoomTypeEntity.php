<?php

namespace RoomTypes\Domain\Entities;

use RoomTypes\Domain\Enums\RoomTypeName;

class RoomTypeEntity
{
    public function __construct(
        private ?int $id,
        private int $hotel_id,
        private RoomTypeName $name,
        private int $max_occupancy,
        private float $base_price,
        private int $total_rooms
    ) {}

    public function getId(): ?int { return $this->id; }
    public function getHotelId(): int { return $this->hotel_id; }
    public function getName(): RoomTypeName { return $this->name; }
    public function getMaxOccupancy(): int { return $this->max_occupancy; }
    public function getBasePrice(): float { return $this->base_price; }
    public function getTotalRooms(): int { return $this->total_rooms; }

    public function toArray(): array
    {
        return [
            "id"            => $this->id,
            "hotel_id"      => $this->hotel_id,
            "name"          => $this->name->value,
            "max_occupancy" => $this->max_occupancy,
            "base_price"    => $this->base_price,
            "total_rooms"   => $this->total_rooms,
        ];
    }
}
