<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportIsksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_isks', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ticket101_id');
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->text('ticket_type')->nullable();
            $table->text('emergency_types')->nullable();
            $table->text('distance_emergency_fd')->nullable();
            $table->text('distance_emergency_city')->nullable();
            $table->text('wind_speed_direction')->nullable();
            $table->text('fire_speed')->nullable();
            $table->text('circs_emergency_escalate')->nullable();
            $table->text('material_damage')->nullable();
            $table->text('animals_died')->nullable();
            $table->text('animals_saved')->nullable();
            $table->text('destroyed')->nullable();
            $table->text('damaged')->nullable();
            $table->text('wealth_saved')->nullable();
            $table->text('destruction_description')->nullable();
            $table->text('liquidation_evolved')->nullable();
            $table->text('gsgz')->nullable();
            $table->text('emergency_hq')->nullable();
            $table->text('emergency_cost')->nullable();
            $table->text('ownership_type')->nullable();
            $table->text('escape_routes')->nullable();
            $table->text('emergency_alarm_systems')->nullable();
            $table->text('circs_caused_emergency')->nullable();
            $table->text('emergency_objects_evolved')->nullable();
            $table->text('guilty_persons_info')->nullable();
            $table->text('investigation_results')->nullable();
            $table->text('gov_investigate_commission')->nullable();
            $table->text('engineering_staff_work')->nullable();
            $table->text('latest_tactical_drill')->nullable();
            $table->text('safety_measures_taken')->nullable();
            $table->text('possible_emergency_causes')->nullable();
            $table->text('pos_negative_emergency_liqv')->nullable();
            $table->text('measures_prevent_emergency')->nullable();
            $table->text('rec_measures_prevent_dead')->nullable();
            $table->text('conclusion')->nullable();

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
        Schema::dropIfExists('report_isks');
    }
}
