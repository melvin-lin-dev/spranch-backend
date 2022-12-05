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
        Schema::create('slides', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('presentation_id');
            $table->string('title', 100);
            $table->string('description', 255);
            $table->uuid('detail_id')->nullable();
            $table->timestamps();

            $table->index(['presentation_id', 'detail_id']);
            $table->foreign('presentation_id')->references('id')->on('presentations');
            $table->foreign('detail_id')->references('id')->on('presentations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slides');
    }
};
