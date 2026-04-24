<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            $table->longText('approach_content')->nullable()->after('description');
            $table->longText('model_content')->nullable()->after('approach_content');
        });
    }

    public function down(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            $table->dropColumn(['approach_content', 'model_content']);
        });
    }
};

