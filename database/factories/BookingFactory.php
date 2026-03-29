<?php

namespace Database\Factories;

use Bookings\Infrastructure\Models\Booking;
use Bookings\Domain\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('now', '+1 month');
        $checkOut = clone $checkIn;
        $checkOut->modify('+' . fake()->numberBetween(1, 5) . ' days');

        return [
            'hotel_id'     => 1, // To be overridden
            'room_type_id' => 1, // To be overridden
            'guest_name'   => fake()->name(),
            'guest_email'  => fake()->safeEmail(),
            'check_in'     => $checkIn->format('Y-m-d'),
            'check_out'    => $checkOut->format('Y-m-d'),
            'rooms_count'  => 1,
            'adults_count' => 2,
            'total_price'  => fake()->randomFloat(2, 100, 1000),
            'status'       => BookingStatus::PENDING,
        ];
    }
}
