<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'page_header_image')) {
                $table->string('page_header_image')->nullable()->after('logo');
            }

            if (! Schema::hasColumn('settings', 'page_header_caption')) {
                $table->text('page_header_caption')->nullable()->after('page_header_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'page_header_caption')) {
                $table->dropColumn('page_header_caption');
            }

            if (Schema::hasColumn('settings', 'page_header_image')) {
                $table->dropColumn('page_header_image');
            }
        });
    }
};
