<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            if (! Schema::hasColumn('backgrounds', 'factory_description')) {
                $table->longText('factory_description')->nullable()->after('model_content');
            }

            if (! Schema::hasColumn('backgrounds', 'factory_services')) {
                $table->longText('factory_services')->nullable()->after('factory_description');
            }

            if (! Schema::hasColumn('backgrounds', 'factory_community_impact')) {
                $table->longText('factory_community_impact')->nullable()->after('factory_services');
            }

            if (! Schema::hasColumn('backgrounds', 'factory_training_facilities')) {
                $table->longText('factory_training_facilities')->nullable()->after('factory_community_impact');
            }
        });
    }

    public function down(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            $drops = [];
            foreach ([
                'factory_training_facilities',
                'factory_community_impact',
                'factory_services',
                'factory_description',
            ] as $column) {
                if (Schema::hasColumn('backgrounds', $column)) {
                    $drops[] = $column;
                }
            }

            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }
};
