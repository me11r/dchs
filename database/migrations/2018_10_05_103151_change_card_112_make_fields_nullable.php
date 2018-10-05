<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112MakeFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->unsignedInteger('crossroad_1_id')->nullable(true)->change();
            $table->unsignedInteger('crossroad_2_id')->nullable(true)->change();
            $table->unsignedInteger('incident_type_id')->nullable(true)->change();
            $table->string('caller_phone')->nullable(true)->change();
            $table->string('caller_name')->nullable(true)->change();
            $table->unsignedInteger('additional_street_id')->nullable(true)->change();
            $table->unsignedInteger('additional_incident_type_id')->nullable(true)->change();
            $table->text('measures')->nullable(true)->change();
            $table->text('resources')->nullable(true)->change();
            $table->integer('injured')->nullable(true)->change();
            $table->integer('evacuated')->nullable(true)->change();
            $table->integer('hospitalized')->nullable(true)->change();
            $table->unsignedInteger('city_area_id')->change();
            $table->string('location', 500)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->unsignedInteger('crossroad_1_id')->nullable(false)->change();
            $table->unsignedInteger('crossroad_2_id')->nullable(false)->change();
            $table->unsignedInteger('incident_type_id')->nullable(false)->change();
            $table->string('caller_phone')->nullable(false)->change();
            $table->string('caller_name')->nullable(false)->change();
            $table->unsignedInteger('additional_street_id')->nullable(false)->change();
            $table->unsignedInteger('additional_incident_type_id')->nullable(false)->change();
            $table->text('measures')->nullable(false)->change();
            $table->text('resources')->nullable(false)->change();
            $table->integer('injured')->nullable(false)->change();
            $table->integer('evacuated')->nullable(false)->change();
            $table->integer('hospitalized')->nullable(false)->change();
            $table->unsignedInteger('city_area_id')->change();
            $table->string('location', 500)->nullable(false)->change();
        });
    }
}
