<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'what_we_do')) {
                $table->longText('what_we_do')->nullable()->after('description');
            }
            if (!Schema::hasColumn('activities', 'how_we_do_it')) {
                $table->longText('how_we_do_it')->nullable()->after('what_we_do');
            }
            if (!Schema::hasColumn('activities', 'impact')) {
                $table->longText('impact')->nullable()->after('how_we_do_it');
            }
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $drop = [];
            foreach (['what_we_do', 'how_we_do_it', 'impact'] as $col) {
                if (Schema::hasColumn('activities', $col)) {
                    $drop[] = $col;
                }
            }
            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }
};
