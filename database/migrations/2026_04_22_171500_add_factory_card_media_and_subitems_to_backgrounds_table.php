<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            if (! Schema::hasColumn('backgrounds', 'factory_services_image')) {
                $table->string('factory_services_image')->nullable()->after('factory_services');
            }
            if (! Schema::hasColumn('backgrounds', 'factory_services_subitems')) {
                $table->longText('factory_services_subitems')->nullable()->after('factory_services_image');
            }

            if (! Schema::hasColumn('backgrounds', 'factory_community_impact_image')) {
                $table->string('factory_community_impact_image')->nullable()->after('factory_community_impact');
            }
            if (! Schema::hasColumn('backgrounds', 'factory_community_impact_subitems')) {
                $table->longText('factory_community_impact_subitems')->nullable()->after('factory_community_impact_image');
            }

            if (! Schema::hasColumn('backgrounds', 'factory_training_facilities_image')) {
                $table->string('factory_training_facilities_image')->nullable()->after('factory_training_facilities');
            }
            if (! Schema::hasColumn('backgrounds', 'factory_training_facilities_subitems')) {
                $table->longText('factory_training_facilities_subitems')->nullable()->after('factory_training_facilities_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            $drops = [];
            foreach ([
                'factory_services_subitems',
                'factory_services_image',
                'factory_community_impact_subitems',
                'factory_community_impact_image',
                'factory_training_facilities_subitems',
                'factory_training_facilities_image',
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
