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
        if (!Schema::hasColumn('testimonies', 'video_url')) {
            Schema::table('testimonies', function (Blueprint $table) {
                $table->string('video_url')->nullable()->after('testimony');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('testimonies', 'video_url')) {
            Schema::table('testimonies', function (Blueprint $table) {
                $table->dropColumn('video_url');
            });
        }
    }
};
