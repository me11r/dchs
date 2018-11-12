<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDistrictManagersAddCityAreaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('district_managers', function (Blueprint $table) {
            $table->unsignedInteger('city_area_id')->after('id')->nullable();
            $table->foreign('city_area_id')
                ->references('id')
                ->on('dict_city_area')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('district_managers', function (Blueprint $table) {
            $table->dropForeign(['city_area_id']);
            $table->dropColumn(['city_area_id']);
        });
    }
}
