<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('backgrounds') || Schema::hasColumn('backgrounds', 'impact_stats')) {
            return;
        }

        Schema::table('backgrounds', function (Blueprint $table) {
            $table->json('impact_stats')->nullable();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('backgrounds') || ! Schema::hasColumn('backgrounds', 'impact_stats')) {
            return;
        }

        Schema::table('backgrounds', function (Blueprint $table) {
            $table->dropColumn('impact_stats');
        });
    }
};
