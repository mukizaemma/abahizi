<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partnership_inquiries', function (Blueprint $table) {
            $table->string('submission_channel', 20)->nullable()->after('message');
        });

        Schema::table('order_requests', function (Blueprint $table) {
            $table->string('submission_channel', 20)->nullable()->after('product_reference');
        });
    }

    public function down(): void
    {
        Schema::table('partnership_inquiries', function (Blueprint $table) {
            $table->dropColumn('submission_channel');
        });

        Schema::table('order_requests', function (Blueprint $table) {
            $table->dropColumn('submission_channel');
        });
    }
};
