<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            if (! Schema::hasColumn('backgrounds', 'home_process_background')) {
                $table->string('home_process_background')->nullable()->after('core_values_background');
            }
            if (! Schema::hasColumn('backgrounds', 'factory_capabilities_background')) {
                $table->string('factory_capabilities_background')->nullable()->after('home_process_background');
            }
            if (! Schema::hasColumn('backgrounds', 'home_why_partner_background')) {
                $table->string('home_why_partner_background')->nullable()->after('factory_capabilities_background');
            }
            if (! Schema::hasColumn('backgrounds', 'product_story_background')) {
                $table->string('product_story_background')->nullable()->after('home_why_partner_background');
            }
            if (! Schema::hasColumn('backgrounds', 'impact_cta_background')) {
                $table->string('impact_cta_background')->nullable()->after('product_story_background');
            }
            if (! Schema::hasColumn('backgrounds', 'programs_dual_cta_background')) {
                $table->string('programs_dual_cta_background')->nullable()->after('impact_cta_background');
            }
        });
    }

    public function down(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            foreach ([
                'home_process_background',
                'factory_capabilities_background',
                'home_why_partner_background',
                'product_story_background',
                'impact_cta_background',
                'programs_dual_cta_background',
            ] as $column) {
                if (Schema::hasColumn('backgrounds', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
