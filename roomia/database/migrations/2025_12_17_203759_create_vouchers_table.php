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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50)->unique();     // ROOMIA10
            $table->string('name', 120)->nullable();  // Tên hiển thị
            $table->text('description')->nullable();

            $table->string('type', 20);               // percent|fixed
            $table->decimal('value', 12, 2);  // % hoặc số tiền

            $table->decimal('min_subtotal', 12, 2)->nullable(); // tối thiểu để áp dụng
            $table->decimal('max_discount', 12, 2)->nullable(); // trần giảm (cho percent)

            $table->unsignedInteger('usage_limit')->nullable();         // tổng lượt dùng
            $table->unsignedInteger('per_user_limit')->nullable();      // mỗi user
            $table->unsignedInteger('used_count')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();

            $table->index(['is_active', 'starts_at', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
