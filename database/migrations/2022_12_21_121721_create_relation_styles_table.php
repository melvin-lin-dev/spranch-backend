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
        Schema::create('relation_styles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('relation_id');
            $table->string('background_color', 7)->default('#000000');
            $table->timestamps();

            $table->index(['relation_id']);
            $table->foreign('relation_id')->references('id')->on('relations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('relation_styles');
    }
};
