<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();

            $table->date('date');
            // null = dùng price mặc định của room
            $table->unsignedInteger('price_per_night')->nullable();
            // null = dùng total_units của room
            $table->unsignedInteger('available_units')->nullable();
            // true = đóng ngày này, không cho book
            $table->boolean('is_closed')->default(false);

            $table->timestamps();

            $table->unique(['room_type_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_calendars');
    }
};
