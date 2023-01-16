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
        Schema::create('slide_parts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('slide_id');
            $table->unsignedTinyInteger('number');
            $table->timestamps();

            $table->index(['slide_id']);
            $table->foreign('slide_id')->references('id')->on('slides')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slide_parts');
    }
};
