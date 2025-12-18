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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();

            // Người gửi (nếu đã login)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Ai xử lý (admin)
            $table->foreignId('handled_by')->nullable()->references('id')->on('users')->nullOnDelete();

            $table->string('name', 120);
            $table->string('email', 190);
            $table->string('subject', 190)->nullable();
            $table->text('message');

            // Trạng thái xử lý
            $table->string('status', 30)->default('new'); // new|seen|replied|closed

            // Metadata chống spam/tra soát
            $table->string('ip_address', 64)->nullable();
            $table->string('user_agent', 512)->nullable();

            // Ghi chú nội bộ
            $table->text('admin_note')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
