<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            if (!Schema::hasColumn('abouts', 'core_values_list')) {
                $table->longText('core_values_list')->nullable()->after('values');
            }
        });

        Schema::table('backgrounds', function (Blueprint $table) {
            if (!Schema::hasColumn('backgrounds', 'core_values_background')) {
                $table->string('core_values_background')->nullable()->after('image2');
            }
        });
    }

    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            if (Schema::hasColumn('abouts', 'core_values_list')) {
                $table->dropColumn('core_values_list');
            }
        });

        Schema::table('backgrounds', function (Blueprint $table) {
            if (Schema::hasColumn('backgrounds', 'core_values_background')) {
                $table->dropColumn('core_values_background');
            }
        });
    }
};
