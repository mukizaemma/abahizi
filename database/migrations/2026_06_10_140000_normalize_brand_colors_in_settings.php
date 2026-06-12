<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $updates = [];

        if (Schema::hasColumn('settings', 'primary_color')) {
            DB::table('settings')
                ->whereNull('primary_color')
                ->orWhere('primary_color', '')
                ->orWhereIn('primary_color', ['#c9a962', '#b87333', '#C9A962'])
                ->update(['primary_color' => '#fad200']);
        }

        if (Schema::hasColumn('settings', 'secondary_color')) {
            DB::table('settings')
                ->whereNull('secondary_color')
                ->orWhere('secondary_color', '')
                ->orWhereIn('secondary_color', ['#1f1f1f', '#2c2c2c', '#1e3737', '#3d2f24'])
                ->update(['secondary_color' => '#000000']);
        }

        if (Schema::hasColumn('settings', 'neutral_color')) {
            DB::table('settings')
                ->whereNull('neutral_color')
                ->orWhere('neutral_color', '')
                ->update(['neutral_color' => '#9a9a9a']);
        }
    }

    public function down(): void
    {
        // Non-destructive brand normalization — no rollback.
    }
};
