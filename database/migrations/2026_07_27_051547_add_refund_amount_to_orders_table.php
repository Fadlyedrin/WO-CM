<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Nominal yang dikembalikan ke klien saat pembatalan (10% dari DP)
            $table->unsignedBigInteger('refund_amount')->default(0)->after('paid_amount');
            // Tanggal refund diproses
            $table->timestamp('refunded_at')->nullable()->after('refund_amount');
            // Catatan pembatalan dari admin
            $table->text('cancellation_notes')->nullable()->after('refunded_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['refund_amount', 'refunded_at', 'cancellation_notes']);
        });
    }
};
