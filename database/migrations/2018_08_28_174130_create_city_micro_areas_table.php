<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityMicroAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_micro_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->unsignedInteger('city_area_id')->nullable();
            $table->foreign('city_area_id')
                ->references('id')
                ->on('dict_city_area')
                ->onDelete('cascade');

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
        Schema::dropIfExists('city_micro_areas');
    }
}
