<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101HqRidesAddRetreatTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_hq_rides', function (Blueprint $table) {
            $table->string('retreat_time')->after('arrive_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101_hq_rides', function (Blueprint $table) {
            $table->dropColumn(['retreat_time']);
        });
    }
}
