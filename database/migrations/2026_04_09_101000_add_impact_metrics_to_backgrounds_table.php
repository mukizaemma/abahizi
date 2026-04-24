<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            $table->string('families_impacted')->nullable()->after('donationContacts');
            $table->string('jobs_created')->nullable()->after('families_impacted');
            $table->string('training_hours')->nullable()->after('jobs_created');
        });
    }

    public function down(): void
    {
        Schema::table('backgrounds', function (Blueprint $table) {
            $table->dropColumn(['families_impacted', 'jobs_created', 'training_hours']);
        });
    }
};

