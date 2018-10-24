<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirRescueReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_rescue_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('head');
            $table->integer('jet_fuel')->nullable();
            $table->integer('radio_stations')->nullable();
            $table->integer('personal_respiratory_protection')->nullable();
            $table->integer('personal_protection')->nullable();
            $table->integer('other_protection')->nullable();

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
        Schema::dropIfExists('air_rescue_reports');
    }
}
