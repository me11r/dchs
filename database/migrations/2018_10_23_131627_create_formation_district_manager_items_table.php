<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormationDistrictManagerItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_district_manager_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')
                ->on('formation_district_managers')
                ->onDelete('cascade');

            $table->unsignedInteger('manager_id');
            $table->foreign('manager_id')
                ->references('id')
                ->on('district_managers')
                ->onDelete('cascade');

            $table->unsignedInteger('city_area_id');
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
        Schema::dropIfExists('formation_district_manager_items');
    }
}
