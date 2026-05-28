<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (! Schema::hasColumn('settings', 'hero_video_url')) {
                    $table->string('hero_video_url')->nullable()->after('font_family');
                }
                if (! Schema::hasColumn('settings', 'hero_poster')) {
                    $table->string('hero_poster')->nullable()->after('hero_video_url');
                }
                if (! Schema::hasColumn('settings', 'hero_headline')) {
                    $table->string('hero_headline')->nullable()->after('hero_poster');
                }
                if (! Schema::hasColumn('settings', 'hero_subheadline')) {
                    $table->text('hero_subheadline')->nullable()->after('hero_headline');
                }
            });
        }

        if (Schema::hasTable('backgrounds')) {
            Schema::table('backgrounds', function (Blueprint $table) {
                if (! Schema::hasColumn('backgrounds', 'handbags_exported')) {
                    $table->string('handbags_exported')->nullable()->after('training_hours');
                }
                if (! Schema::hasColumn('backgrounds', 'artisans_count')) {
                    $table->string('artisans_count')->nullable()->after('handbags_exported');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $cols = ['hero_video_url', 'hero_poster', 'hero_headline', 'hero_subheadline'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('settings', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('backgrounds')) {
            Schema::table('backgrounds', function (Blueprint $table) {
                foreach (['handbags_exported', 'artisans_count'] as $col) {
                    if (Schema::hasColumn('backgrounds', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
