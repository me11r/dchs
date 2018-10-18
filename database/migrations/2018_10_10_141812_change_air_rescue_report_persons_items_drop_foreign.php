<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAirRescueReportPersonsItemsDropForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('air_rescue_report_persons_items', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::table('air_rescue_report_persons_items', function (Blueprint $table) {
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
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

        Schema::table('air_rescue_report_persons_items', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::table('air_rescue_report_persons_items', function (Blueprint $table) {
            $table->foreign('staff_id')
                ->references('id')
                ->on('air_rescue_reports')
                ->onDelete('cascade');
        });
    }
}
