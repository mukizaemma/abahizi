<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (! Schema::hasColumn('activities', 'involvement_ways')) {
                $table->text('involvement_ways')->nullable()->after('status');
            }
        });

        $defaults = json_encode([
            ['slug' => 'volunteer', 'label' => 'Volunteer', 'kind' => 'standard'],
            ['slug' => 'training', 'label' => 'Offer training materials', 'kind' => 'standard'],
            ['slug' => 'partner', 'label' => 'Become our partner', 'kind' => 'standard'],
            ['slug' => 'donate', 'label' => 'Just donate', 'kind' => 'donate'],
        ]);

        if (Schema::hasColumn('activities', 'involvement_ways')) {
            DB::table('activities')->whereNull('involvement_ways')->update([
                'involvement_ways' => $defaults,
            ]);
        }

        if (! Schema::hasTable('initiative_involvements')) {
            Schema::create('initiative_involvements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
                $table->string('names');
                $table->string('email');
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('involvement_slug');
                $table->string('involvement_label');
                $table->string('involvement_kind')->default('standard');
                $table->text('note')->nullable();
                $table->string('donation_amount')->nullable();
                $table->string('donation_period')->nullable();
                $table->string('submission_channel')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('initiative_involvements');

        Schema::table('activities', function (Blueprint $table) {
            if (Schema::hasColumn('activities', 'involvement_ways')) {
                $table->dropColumn('involvement_ways');
            }
        });
    }
};
