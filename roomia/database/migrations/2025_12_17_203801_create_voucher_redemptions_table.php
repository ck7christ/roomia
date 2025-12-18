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
        Schema::create('voucher_redemptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('voucher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Nếu bạn muốn gắn với booking: mở comment nếu bảng bookings có tồn tại
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            $table->index(['voucher_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_redemptions');
    }
};
