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
        Schema::create('presentation_styles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('presentation_id');
            $table->string('background_color', 7)->default('#D1D5DB');
            $table->string('selected_element_color', 7)->default('#FFFFFF');
            $table->timestamps();

            $table->index(['presentation_id']);
            $table->foreign('presentation_id')->references('id')->on('presentations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presentation_styles');
    }
};
