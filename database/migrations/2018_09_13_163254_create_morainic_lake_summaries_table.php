<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMorainicLakeSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('morainic_lake_summaries', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('morainic_lake_id');
            $table->foreign('morainic_lake_id')
                ->references('id')
                ->on('morainic_lakes')
                ->onDelete('cascade');

            $table->string('initial_volume')->nullable();
            $table->string('current_volume')->nullable();
            $table->string('inflow_glacier')->nullable();
            $table->string('drainage_via_evacuation_channel')->nullable();
            $table->string('drainage_via_pump')->nullable();
            $table->string('drainage_via_siphon')->nullable();
            $table->string('water_dropped_day')->nullable();
            $table->string('water_dropped_total')->nullable();
            $table->string('lowering_from_initial1')->nullable();
            $table->string('lowering_from_initial2')->nullable();
            $table->string('temperature_water')->nullable();
            $table->string('temperature_air')->nullable();
            $table->string('zero_isotherm')->nullable();
            $table->date('date')->nullable();

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
        Schema::dropIfExists('morainic_lake_summaries');
    }
}
