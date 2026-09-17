<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'landing_copy')) {
                $table->json('landing_copy')->nullable()->after('page_headers');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'landing_copy')) {
                $table->dropColumn('landing_copy');
            }
        });
    }
};
