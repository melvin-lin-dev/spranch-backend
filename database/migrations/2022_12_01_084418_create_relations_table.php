<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('presentation_id');
            $table->uuid('slide_part1_id');
            $table->uuid('slide_part2_id');
            $table->string('title1', 100)->nullable();
            $table->string('title2', 100)->nullable();
            $table->timestamps();

            $table->index(['presentation_id', 'slide_part1_id', 'slide_part2_id']);
            $table->foreign('presentation_id')->references('id')->on('presentations')->cascadeOnDelete();
            $table->foreign('slide_part1_id')->references('id')->on('slide_parts');
            $table->foreign('slide_part2_id')->references('id')->on('slide_parts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relations');
    }
};
