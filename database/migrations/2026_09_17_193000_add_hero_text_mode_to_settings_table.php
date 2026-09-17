<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings') || Schema::hasColumn('settings', 'hero_text_mode')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->string('hero_text_mode', 20)->nullable()->after('hero_media_type');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings') || ! Schema::hasColumn('settings', 'hero_text_mode')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('hero_text_mode');
        });
    }
};
