<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_story_settings', function (Blueprint $table) {
            $table->id();
            $table->string('heading', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('product_story_points', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_story_points');
        Schema::dropIfExists('product_story_settings');
    }
};
