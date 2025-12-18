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
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->unsignedBigInteger('cancelled_by_id')->nullable()->after('cancelled_at');
            $table->string('cancelled_by_type')->nullable()->after('cancelled_by_id'); // 'guest', 'host', 'admin'
            $table->text('cancel_reason')->nullable()->after('cancelled_by_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
            $table->dropColumn([
                'cancelled_at',
                'cancelled_by_id',
                'cancelled_by_type',
                'cancel_reason',
            ]);
        });
    }
};
