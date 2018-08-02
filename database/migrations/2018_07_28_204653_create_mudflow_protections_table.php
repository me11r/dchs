<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMudflowProtectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mudflow_protections', function (Blueprint $table) {
            $table->increments('id');
            $table->text('information')->nullable();
            $table->integer('gauging_station_id')->unsigned();
            $table->float('water_flow_rate')->nullable();
            $table->float('critical_water_flow_rate')->nullable();
            $table->float('turbidity_of_water')->nullable();
            $table->float('max_turbidity_of_water')->nullable();
            $table->float('air_temperature')->nullable();
            $table->float('water_temperature')->nullable();
            $table->float('precipitation')->nullable();
            $table->float('height_of_snow')->nullable();
            $table->text('weather')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('gauging_station_id')->name('gsig')->references('id')->on('gauging_stations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mudflow_protections');
    }
}
