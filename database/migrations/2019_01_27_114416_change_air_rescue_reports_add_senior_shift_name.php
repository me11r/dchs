<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAirRescueReportsAddSeniorShiftName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->string('senior_shift_name')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->dropColumn(['senior_shift_name']);
        });
    }
}
