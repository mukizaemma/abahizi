<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('impacts', function (Blueprint $table) {
            if (!Schema::hasColumn('impacts', 'value')) {
                $table->string('value')->nullable()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('impacts', function (Blueprint $table) {
            if (Schema::hasColumn('impacts', 'value')) {
                $table->dropColumn('value');
            }
        });
    }
};
