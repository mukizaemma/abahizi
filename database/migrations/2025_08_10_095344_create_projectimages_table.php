<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projectimages', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('caption')->nullable();

            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->foreign("added_by")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("activity_id")->references("id")->on("activities")->onDelete("cascade");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projectimages');
    }
};
