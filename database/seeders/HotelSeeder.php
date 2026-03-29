<?php

namespace Database\Seeders;

use Hotels\Infrastructure\Models\Hotel;
use Hotels\Domain\Enums\HotelStatus;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        // Seed some specific cities mentioned in requirements
        $cities = ['Paris', 'London', 'Berlin', 'New York'];

        foreach ($cities as $city) {
            Hotel::factory()->create([
                'city' => $city,
                'name' => $city . ' Grand Hotel',
            ]);
        }

        // Add 1 inactive hotel
        Hotel::factory()->create([
            'status' => HotelStatus::INACTIVE,
            'name'   => 'Hidden Resort',
        ]);
    }
}
