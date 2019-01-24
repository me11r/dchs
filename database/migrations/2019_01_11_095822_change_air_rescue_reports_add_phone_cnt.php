<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAirRescueReportsAddPhoneCnt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->string('staff_head_count')->nullable()->after('id');
            $table->string('staff_head_phone')->nullable()->after('id');
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
            $table->dropColumn([
                'staff_head_count',
                'staff_head_phone',
            ]);
        });
    }
}
