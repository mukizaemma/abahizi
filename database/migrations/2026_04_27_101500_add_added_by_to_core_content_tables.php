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
        foreach (['programs', 'activities', 'testimonies', 'news'] as $tableName) {
            if (!Schema::hasColumn($tableName, 'added_by')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('added_by')->nullable()->after('image');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['programs', 'activities', 'testimonies', 'news'] as $tableName) {
            if (Schema::hasColumn($tableName, 'added_by')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('added_by');
                });
            }
        }
    }
};
