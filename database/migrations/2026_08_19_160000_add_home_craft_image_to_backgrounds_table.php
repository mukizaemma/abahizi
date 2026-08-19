<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('backgrounds')) {
            return;
        }

        Schema::table('backgrounds', function (Blueprint $table) {
            if (! Schema::hasColumn('backgrounds', 'home_craft_image')) {
                $table->string('home_craft_image')->nullable()->after('home_why_partner_background');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('backgrounds') || ! Schema::hasColumn('backgrounds', 'home_craft_image')) {
            return;
        }

        Schema::table('backgrounds', function (Blueprint $table) {
            $table->dropColumn('home_craft_image');
        });
    }
};
