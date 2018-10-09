<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirRescueReportTechItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('air_rescue_report_tech_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aircraft_id');
            $table->foreign('aircraft_id')
                ->references('id')
                ->on('aircrafts')
                ->onDelete('cascade');

            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')
                ->on('air_rescue_reports')
                ->onDelete('cascade');

            $table->string('status');
            $table->string('reserve')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->text('comment')->nullable();

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
        Schema::dropIfExists('air_rescue_report_tech_items');
    }
}
