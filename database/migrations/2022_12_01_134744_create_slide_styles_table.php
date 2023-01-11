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
        Schema::create('slide_styles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('slide_id');
            $table->integer('top');
            $table->integer('left');
            $table->integer('width')->default(100);
            $table->integer('height')->default(100);
            $table->string('border_color', 7)->default('#000000');
            $table->string('background_color', 7)->default('#FFFFFF');
            $table->string('part_color', 7)->default('#FFFFFF');
            $table->string('part_background_color', 7)->default('#46B3E4');
            $table->string('part_used_color', 7)->default('#000000');
            $table->string('part_used_background_color', 7)->default('#FFFFFF');
            $table->integer('z_index');
            $table->timestamps();

            $table->index(['slide_id']);
            $table->foreign('slide_id')->references('id')->on('slides');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slide_styles');
    }
};
