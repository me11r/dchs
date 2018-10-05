<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dict_city_area', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('dict_fire_object', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('dict_fire_level', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('dict_liquidation_method', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('dict_burn_object', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('dict_trip_result', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('service_types', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('incident_types', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('dict_operational_plan', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('dict_trunk', function (Blueprint $table){
            $table->softDeletes();
        });
        Schema::table('special_plans', function (Blueprint $table){
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
