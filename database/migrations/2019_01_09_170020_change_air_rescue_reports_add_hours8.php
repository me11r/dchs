<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAirRescueReportsAddHours8 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->dropColumn(['staff_head']);
        });

        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->integer('staff_duty_shift_8hours')->after('staff_duty_shift')->nullable();
            $table->string('staff_head')->after('staff_action')->nullable();
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
            $table->dropColumn(['staff_head','staff_duty_shift_8hours']);
        });

        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->integer('staff_head')->after('staff_action')->nullable()->default(0);
        });
    }
}
