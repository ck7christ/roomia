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
        Schema::table('payments', function (Blueprint $table) {
            //
            $table->timestamp('refunded_at')->nullable()->after('paid_at');
            $table->string('refund_id')->nullable()->after('refunded_at');        // id refund bên Stripe
            $table->bigInteger('refund_amount')->nullable()->after('refund_id');  // số tiền refund (đơn vị nhỏ nhất)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
            $table->dropColumn([
                'refunded_at',
                'refund_id',
                'refund_amount',
            ]);
        });
    }
};
