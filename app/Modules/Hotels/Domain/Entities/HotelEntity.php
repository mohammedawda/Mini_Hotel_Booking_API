<?php

namespace Hotels\Domain\Entities;

use Hotels\Domain\Enums\HotelStatus;

class HotelEntity
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $city,
        private string $address,
        private int $rating,
        private HotelStatus $status
    ) {}

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCity(): string { return $this->city; }
    public function getAddress(): string { return $this->address; }
    public function getRating(): int { return $this->rating; }
    public function getStatus(): HotelStatus { return $this->status; }

    public function toArray(): array
    {
        return [
            "id"      => $this->id,
            "name"    => $this->name,
            "city"    => $this->city,
            "address" => $this->address,
            "rating"  => $this->rating,
            "status"  => $this->status->value,
        ];
    }
}
