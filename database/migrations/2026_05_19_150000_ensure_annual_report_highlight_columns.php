<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('annual_reports')) {
            return;
        }

        Schema::table('annual_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('annual_reports', 'highlight_title')) {
                $table->string('highlight_title')->nullable()->after('description');
            }
            if (! Schema::hasColumn('annual_reports', 'highlight_message')) {
                $table->longText('highlight_message')->nullable()->after(
                    Schema::hasColumn('annual_reports', 'highlight_title') ? 'highlight_title' : 'description'
                );
            }
            if (! Schema::hasColumn('annual_reports', 'pdf_button_label')) {
                $table->string('pdf_button_label')->nullable()->after(
                    Schema::hasColumn('annual_reports', 'highlight_message') ? 'highlight_message' : 'description'
                );
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
        // Leave data in place; earlier migrations own rollback.
    }
};
