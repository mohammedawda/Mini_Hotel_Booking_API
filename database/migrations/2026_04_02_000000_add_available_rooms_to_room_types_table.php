<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('room_types')) {
            return;
        }

        if (!Schema::hasColumn('room_types', 'available_rooms')) {
            Schema::table('room_types', function (Blueprint $table) {
                $table->unsignedInteger('available_rooms')->after('total_rooms')->default(0);
            });
        }

        // Initialize available_rooms = total_rooms - sum of rooms_count
        // for active (pending/confirmed) bookings per room type.
        DB::statement("
            UPDATE room_types rt
            LEFT JOIN (
                SELECT room_type_id, SUM(rooms_count) AS booked
                FROM bookings
                WHERE status IN ('pending', 'confirmed')
                GROUP BY room_type_id
            ) b ON b.room_type_id = rt.id
            SET rt.available_rooms = rt.total_rooms - COALESCE(b.booked, 0)
        ");
    }

    public function down(): void
    {
        if (Schema::hasTable('room_types') && Schema::hasColumn('room_types', 'available_rooms')) {
            Schema::table('room_types', function (Blueprint $table) {
                $table->dropColumn('available_rooms');
            });
        }
    }
};
