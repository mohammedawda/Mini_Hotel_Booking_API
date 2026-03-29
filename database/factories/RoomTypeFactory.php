<?php

namespace Database\Factories;

use RoomTypes\Infrastructure\Models\RoomType;
use RoomTypes\Domain\Enums\RoomTypeName;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    public function definition(): array
    {
        return [
            'name'          => fake()->randomElement(RoomTypeName::cases()),
            'max_occupancy' => fake()->numberBetween(1, 4),
            'base_price'    => fake()->randomFloat(2, 50, 500),
            'total_rooms'   => fake()->numberBetween(5, 50),
            'status'        => 'active',
        ];
    }
}
