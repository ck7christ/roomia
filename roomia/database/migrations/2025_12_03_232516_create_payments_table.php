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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Gắn với booking
            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            // Số tiền (nên lưu đơn vị nhỏ nhất, ví dụ VND)
            $table->unsignedBigInteger('amount');
            $table->string('currency', 10)->default('VND');

            // Hình thức thanh toán: 'cod', 'vnpay', 'momo', ...
            $table->string('method');

            // Thông tin từ cổng thanh toán (online thì có, COD thì null)
            $table->string('provider_transaction_id')->nullable();

            // Trạng thái: pending, success, failed, refunded...
            $table->string('status')->default('pending');

            // Lưu raw response / info thêm nếu cần
            $table->json('meta')->nullable();

            // Thời điểm thực sự thanh toán xong
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
