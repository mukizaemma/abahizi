<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('factory_gallery_images')) {
            return;
        }

        Schema::create('factory_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('caption')->nullable();
            $table->string('image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factory_gallery_images');
    }
};
