<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            if (!Schema::hasColumn('backgrounds', 'problem_statement')) {
                $table->longText('problem_statement')->nullable()->after('model_content');
            }
            if (!Schema::hasColumn('backgrounds', 'solution_statement')) {
                $table->longText('solution_statement')->nullable()->after('problem_statement');
            }
            if (!Schema::hasColumn('backgrounds', 'what_we_do')) {
                $table->longText('what_we_do')->nullable()->after('solution_statement');
            }
            if (!Schema::hasColumn('backgrounds', 'how_it_works')) {
                $table->longText('how_it_works')->nullable()->after('what_we_do');
            }
            if (!Schema::hasColumn('backgrounds', 'expertise_content')) {
                $table->longText('expertise_content')->nullable()->after('how_it_works');
            }
            if (!Schema::hasColumn('backgrounds', 'manufacturing_impact_content')) {
                $table->longText('manufacturing_impact_content')->nullable()->after('expertise_content');
            }
            if (!Schema::hasColumn('backgrounds', 'products_intro')) {
                $table->longText('products_intro')->nullable()->after('manufacturing_impact_content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            $drop = [];
            foreach ([
                'problem_statement',
                'solution_statement',
                'what_we_do',
                'how_it_works',
                'expertise_content',
                'manufacturing_impact_content',
                'products_intro',
            ] as $col) {
                if (Schema::hasColumn('backgrounds', $col)) {
                    $drop[] = $col;
                }
            }
            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }
};
