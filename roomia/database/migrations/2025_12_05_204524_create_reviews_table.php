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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Booking được review
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            // RoomType được review
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');

            // Guest viết review
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Host nhận review (để host xem cho dễ)
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade');

            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();

            $table->timestamps();

            // 1 booking chỉ có 1 review
            $table->unique('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
