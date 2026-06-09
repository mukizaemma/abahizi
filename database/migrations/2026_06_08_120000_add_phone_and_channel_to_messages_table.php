<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (! Schema::hasColumn('messages', 'phone')) {
                $table->string('phone', 64)->nullable()->after('email');
            }
            if (! Schema::hasColumn('messages', 'submission_channel')) {
                $table->string('submission_channel', 20)->nullable()->after('message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $drops = [];
            foreach (['submission_channel', 'phone'] as $column) {
                if (Schema::hasColumn('messages', $column)) {
                    $drops[] = $column;
                }
            }
            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }
};
