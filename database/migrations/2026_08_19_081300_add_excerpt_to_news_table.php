<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('news')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            if (! Schema::hasColumn('news', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('body');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('news') || ! Schema::hasColumn('news', 'excerpt')) {
            return;
        }

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('excerpt');
        });
    }
};
