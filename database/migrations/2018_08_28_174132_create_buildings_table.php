<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index()->nullable();

            $table->unsignedInteger('city_area_id');
            $table->foreign('city_area_id')
                ->references('id')
                ->on('dict_city_area')
                ->onDelete('cascade');

            $table->string('building_number')->nullable();

            $table->unsignedInteger('street_id')->nullable();
            $table->foreign('street_id')
                ->references('id')
                ->on('streets')
                ->onDelete('cascade');

            $table->unsignedInteger('city_micro_area_id')->nullable();
            $table->foreign('city_micro_area_id')
                ->references('id')
                ->on('city_micro_areas')
                ->onDelete('cascade');

            $table->unsignedInteger('object_type_id')->nullable();
            $table->foreign('object_type_id')
                ->references('id')
                ->on('dict_burn_object')
                ->onDelete('cascade');

            $table->string('year_of_development')->nullable();
            $table->integer('number_of_storeys')->default(1);
            $table->double('square')->nullable();
            $table->double('square_total')->nullable();

            $table->unsignedInteger('wall_material_id')->nullable();
            $table->foreign('wall_material_id')
                ->references('id')
                ->on('wall_materials')
                ->onDelete('cascade');

            $table->text('features')->nullable();

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
        Schema::dropIfExists('buildings');
    }
}
