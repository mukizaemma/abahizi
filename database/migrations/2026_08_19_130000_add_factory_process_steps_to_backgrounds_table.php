<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            if (! Schema::hasColumn('backgrounds', 'factory_process_steps')) {
                $table->longText('factory_process_steps')->nullable()->after('factory_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            if (Schema::hasColumn('backgrounds', 'factory_process_steps')) {
                $table->dropColumn('factory_process_steps');
            }
        });
    }
};
