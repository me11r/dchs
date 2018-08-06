<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_plans', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('fire_level_id')->unsigned();
            $table->foreign('fire_level_id')->name('flidfl')
                ->references('id')->on('dict_fire_level')->onDelete('cascade');

            $table->integer('city_area_id')->unsigned();
            $table->foreign('city_area_id')->name('caidca')
                ->references('id')->on('dict_city_area')->onDelete('cascade');

            $table->string('object_name');

            $table->integer('fire_department_id')->unsigned();
            $table->foreign('fire_department_id')->name('fdi')
                ->references('id')->on('fire_departments')->onDelete('cascade');

            $table->integer('operational_plan_id')->unsigned();
            $table->foreign('operational_plan_id')->name('opidop')
                ->references('id')->on('dict_operational_plan')->onDelete('cascade');

            $table->string('location');

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
        Schema::dropIfExists('special_plans');
    }
}
