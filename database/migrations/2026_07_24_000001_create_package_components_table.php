<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('name');           // e.g. "Dekorasi Pelaminan"
            $table->string('icon')->default('bi-star'); // Bootstrap icon class
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price'); // Component value/price
            $table->boolean('is_optional')->default(true); // Can be unchecked?
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_components');
    }
};
