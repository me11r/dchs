<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeChronology101sAddTicket101HqRides extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chronology101s', function (Blueprint $table) {
            $table->unsignedInteger('hq_ride_id')->nullable()->after('fire_department_result_id');

            $table->foreign('hq_ride_id')
                ->references('id')
                ->on('ticket101_hq_rides')
                ->onDelete('cascade');

            $table->unsignedInteger('fire_department_result_id')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chronology101s', function (Blueprint $table) {
            $table->dropForeign(['hq_ride_id']);
            $table->dropColumn(['hq_ride_id']);
            $table->unsignedInteger('fire_department_result_id')->nullable(false)->change();
        });
    }
}
