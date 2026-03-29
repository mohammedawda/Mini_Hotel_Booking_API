<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            HotelSeeder::class,
            RoomTypeSeeder::class,
        ]);

        // Seed an admin user
        \Users\Infrastructure\Models\User::factory()->create([
            'name'  => 'Test Admin',
            'email' => 'admin@example.com',
        ]);

        // Seed 10 random users
        \Users\Infrastructure\Models\User::factory(10)->create();

        // Seed 20 random bookings
        \Bookings\Infrastructure\Models\Booking::factory(20)->create();
    }
}
