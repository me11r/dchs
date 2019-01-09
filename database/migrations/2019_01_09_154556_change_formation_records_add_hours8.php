<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationRecordsAddHours8 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_records', function (Blueprint $table) {
            $table->integer('staff_duty_shift_8hours')->after('staff_duty_shift')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_records', function (Blueprint $table) {
            $table->dropColumn(['staff_duty_shift_8hours']);
        });
    }
}
