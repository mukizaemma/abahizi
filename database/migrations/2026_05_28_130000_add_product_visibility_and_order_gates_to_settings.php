<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'show_products_page')) {
                $table->boolean('show_products_page')->default(true)->after('show_products_publicly');
            }

            if (! Schema::hasColumn('settings', 'accept_order_requests')) {
                $table->boolean('accept_order_requests')->default(true)->after('show_products_page');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'accept_order_requests')) {
                $table->dropColumn('accept_order_requests');
            }
            if (Schema::hasColumn('settings', 'show_products_page')) {
                $table->dropColumn('show_products_page');
            }
        });
    }
};

