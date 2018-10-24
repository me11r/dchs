<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOperDutyShiftStaffItemsForeignSwitch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oper_duty_shift_staff_items', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::table('oper_duty_shift_staff_items', function (Blueprint $table) {

            $table->foreign('staff_id')
                ->references('id')
                ->on('oper_duty_shift_staffs')
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
        Schema::table('oper_duty_shift_staff_items', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        Schema::table('oper_duty_shift_staff_items', function (Blueprint $table) {
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onDelete('cascade');
        });
    }
}
