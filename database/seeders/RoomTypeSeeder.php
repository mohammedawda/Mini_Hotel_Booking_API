<?php

namespace Database\Seeders;

use Hotels\Infrastructure\Models\Hotel;
use RoomTypes\Infrastructure\Models\RoomType;
use RoomTypes\Domain\Enums\RoomTypeName;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            // Each hotel gets 3 room types
            RoomType::factory()->create([
                'hotel_id'      => $hotel->id,
                'name'          => RoomTypeName::SINGLE,
                'max_occupancy' => 1,
                'base_price'    => 100,
            ]);

            RoomType::factory()->create([
                'hotel_id'      => $hotel->id,
                'name'          => RoomTypeName::DOUBLE,
                'max_occupancy' => 2,
                'base_price'    => 180,
            ]);

            RoomType::factory()->create([
                'hotel_id'      => $hotel->id,
                'name'          => RoomTypeName::SUITE,
                'max_occupancy' => 4,
                'base_price'    => 350,
            ]);
        }
    }
}
