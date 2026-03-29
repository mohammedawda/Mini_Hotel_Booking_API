<?php

namespace Database\Factories;

use Hotels\Infrastructure\Models\Hotel;
use Hotels\Domain\Enums\HotelStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition(): array
    {
        return [
            'name'    => fake()->company() . ' Hotel',
            'city'    => fake()->randomElement(['Paris', 'London', 'New York', 'Dubai', 'Tokyo']),
            'address' => fake()->address(),
            'rating'  => fake()->numberBetween(1, 5),
            'status'  => HotelStatus::ACTIVE,
        ];
    }
}
