<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiverAndGaugingStationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        Schema::create('gauging_stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('river_id')->unsigned();

            $table->foreign('river_id')->name('rir')->references('id')->on('rivers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gauging_stations');
        Schema::dropIfExists('rivers');
    }
}
