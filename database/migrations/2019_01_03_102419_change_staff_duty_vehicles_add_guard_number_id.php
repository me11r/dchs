<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStaffDutyVehiclesAddGuardNumberId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_duty_vehicles', function (Blueprint $table) {
            $table->unsignedInteger('guard_number_id')->nullable()->after('id');
            $table->foreign('guard_number_id')
                ->references('id')
                ->on('guard_numbers')
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
        Schema::table('staff_duty_vehicles', function (Blueprint $table) {
            $table->dropForeign(['guard_number_id']);
            $table->dropColumn(['guard_number_id']);
        });
    }
}
