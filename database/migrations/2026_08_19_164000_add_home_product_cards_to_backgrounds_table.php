<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('backgrounds')) {
            return;
        }

        Schema::table('backgrounds', function (Blueprint $table) {
            foreach ([1, 2, 3] as $slot) {
                $image = 'home_product_card_' . $slot . '_image';
                $title = 'home_product_card_' . $slot . '_title';
                if (! Schema::hasColumn('backgrounds', $image)) {
                    $table->string($image)->nullable();
                }
                if (! Schema::hasColumn('backgrounds', $title)) {
                    $table->string($title)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('backgrounds')) {
            return;
        }

        Schema::table('backgrounds', function (Blueprint $table) {
            $columns = [];
            foreach ([1, 2, 3] as $slot) {
                $image = 'home_product_card_' . $slot . '_image';
                $title = 'home_product_card_' . $slot . '_title';
                if (Schema::hasColumn('backgrounds', $image)) {
                    $columns[] = $image;
                }
                if (Schema::hasColumn('backgrounds', $title)) {
                    $columns[] = $title;
                }
            }
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
