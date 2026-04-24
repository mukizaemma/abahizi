<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('primary_color', 9)->nullable()->after('logo');
            $table->string('secondary_color', 9)->nullable()->after('primary_color');
            $table->string('neutral_color', 9)->nullable()->after('secondary_color');
            $table->string('font_family')->nullable()->after('neutral_color');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['primary_color', 'secondary_color', 'neutral_color', 'font_family']);
        });
    }
};

