<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annual_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('annual_reports', 'highlight_title')) {
                $table->string('highlight_title')->nullable()->after('description');
            }
            if (! Schema::hasColumn('annual_reports', 'highlight_message')) {
                $table->longText('highlight_message')->nullable()->after('highlight_title');
            }
            if (! Schema::hasColumn('annual_reports', 'pdf_button_label')) {
                $table->string('pdf_button_label')->nullable()->after('highlight_message');
            }
        });

        if (! Schema::hasTable('annual_report_images')) {
            Schema::create('annual_report_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('annual_report_id')->constrained('annual_reports')->cascadeOnDelete();
                $table->string('image');
                $table->string('caption')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_report_images');

        Schema::table('annual_reports', function (Blueprint $table) {
            foreach (['highlight_title', 'highlight_message', 'pdf_button_label'] as $col) {
                if (Schema::hasColumn('annual_reports', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
