<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOperDutyShiftStaffsAddCityPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oper_duty_shift_staffs', function (Blueprint $table) {
            $table->string('position')->after('id')->nullable();
            $table->string('city')->after('id')->nullable();
            $table->string('rank')->after('id')->nullable()->comment('Должность');
            $table->string('military_rank')->after('id')->nullable()->comment('Звание');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oper_duty_shift_staffs', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'position',
                    'city',
                    'rank',
                    'military_rank',
                ]
            );
        });
    }
}
