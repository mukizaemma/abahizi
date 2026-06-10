<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            if (! Schema::hasColumn('teams', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('display');
            }
        });

        if (Schema::hasColumn('teams', 'sort_order')) {
            $rows = DB::table('teams')->orderBy('created_at')->orderBy('id')->get(['id']);
            foreach ($rows as $index => $row) {
                DB::table('teams')->where('id', $row->id)->update(['sort_order' => $index + 1]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            if (Schema::hasColumn('teams', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
