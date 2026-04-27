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
        if (!Schema::hasColumn('settings', 'google_map_embed_code')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->text('google_map_embed_code')->nullable()->after('youtube');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('settings', 'google_map_embed_code')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('google_map_embed_code');
            });
        }
    }
};
