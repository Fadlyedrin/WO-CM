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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->date('wedding_date');
             $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
             $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->text('notes')->nullable();
            $table->json('excluded_components')->nullable();
            $table->json('vendor_notes')->nullable();
            $table->unsignedBigInteger('component_discount')->default(0);
            $table->enum('payment_type', ['full', 'dp'])->default('full');
            $table->decimal('total_price', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->string('snap_token')->nullable();
            $table->timestamps();
            $table->foreignId('promo_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
