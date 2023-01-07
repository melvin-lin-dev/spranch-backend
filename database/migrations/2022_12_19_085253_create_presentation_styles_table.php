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
            $table->string('first_slide_border_color', 7)->default('#000000');
            $table->string('first_slide_background_color', 7)->default('#B3DDFF');
            $table->string('first_slide_part_color', 7)->default('#FFFFFF');
            $table->string('first_slide_part_background_color', 7)->default('#F6B3A4');
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
