<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impact_report_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Impact Reports');
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('annual_reports', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('description')->nullable();
            $table->string('pdf')->nullable();
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_reports');
        Schema::dropIfExists('impact_report_pages');
    }
};
