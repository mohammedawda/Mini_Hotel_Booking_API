<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes to hotels table
        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                if (!Schema::hasColumn('hotels', 'status')) {
                    $table->enum('status', ['active', 'inactive'])->default('active');
                }
                if (!Schema::hasColumn('hotels', 'city')) {
                    $table->string('city', 45);
                }
                $table->index(['city', 'status']);
            });
        }

        // Add status and indexes to room_types table
        if (Schema::hasTable('room_types')) {
            Schema::table('room_types', function (Blueprint $table) {
                if (!Schema::hasColumn('room_types', 'status')) {
                    $table->enum('status', ['active', 'inactive'])->default('active')->after('name');
                }
                $table->index(['hotel_id', 'max_occupancy', 'status']);
                $table->index(['base_price']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('room_types')) {
            Schema::table('room_types', function (Blueprint $table) {
                $table->dropIndex(['hotel_id', 'max_occupancy', 'status']);
                $table->dropIndex(['base_price']);
                if (Schema::hasColumn('room_types', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }

        if (Schema::hasTable('hotels')) {
            Schema::table('hotels', function (Blueprint $table) {
                $table->dropIndex(['city', 'status']);
            });
        }
    }
};
