<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('handover_feedbacks')) {
            return;
        }

        Schema::table('handover_feedbacks', function (Blueprint $table) {
            if (! Schema::hasColumn('handover_feedbacks', 'rating')) {
                $table->unsignedTinyInteger('rating')->nullable()->after('intent');
            }
            if (! Schema::hasColumn('handover_feedbacks', 'rating_site')) {
                $table->unsignedTinyInteger('rating_site')->nullable()->after('rating');
            }
            if (! Schema::hasColumn('handover_feedbacks', 'rating_admin')) {
                $table->unsignedTinyInteger('rating_admin')->nullable()->after('rating_site');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('handover_feedbacks')) {
            return;
        }

        Schema::table('handover_feedbacks', function (Blueprint $table) {
            foreach (['rating', 'rating_site', 'rating_admin'] as $column) {
                if (Schema::hasColumn('handover_feedbacks', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
