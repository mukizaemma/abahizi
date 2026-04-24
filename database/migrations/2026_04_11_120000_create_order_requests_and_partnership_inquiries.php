<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone', 64);
            $table->string('email');
            $table->text('product_description');
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_reference', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('partnership_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('organization')->nullable();
            $table->string('full_name');
            $table->string('phone', 64);
            $table->string('email');
            $table->text('interests')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_inquiries');
        Schema::dropIfExists('order_requests');
    }
};
