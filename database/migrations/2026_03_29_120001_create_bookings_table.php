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
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
                $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
                $table->string('guest_name');
                $table->string('guest_email');
                $table->date('check_in');
                $table->date('check_out');
                $table->unsignedInteger('rooms_count');
                $table->unsignedInteger('adults_count');
                $table->decimal('total_price', 12, 2);
                $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
                $table->timestamps();

                $table->index(['check_in', 'check_out']);
                $table->index(['hotel_id', 'room_type_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
