<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStaffAddSoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'staff_cpps',
            'staff_crbs',
            'staff_dspts',
            'staff_doctors',
            'staff_duty_vehicles',
            'staff_edds',
            'staff_gdzs_bases',
            'staff_ipls',
            'staff_kshms',
            'staff_senior_communication_masters',
            'staff_water_canals',
            'staff_zhalins',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = [
            'staff',
            'staff_cpps',
            'staff_crbs',
            'staff_dspts',
            'staff_doctors',
            'staff_duty_vehicles',
            'staff_edds',
            'staff_gdzs_bases',
            'staff_ipls',
            'staff_kshms',
            'staff_senior_communication_masters',
            'staff_water_canals',
            'staff_zhalins',
            'oper_duty_shift_staffs',
        ];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

    }
}
