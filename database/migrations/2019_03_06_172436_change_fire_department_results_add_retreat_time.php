<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentResultsAddRetreatTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->timestamp('retreat_time')->nullable()->after('liqv_time');
            $table->boolean('need_check_retreat')->nullable()->after('liqv_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->dropColumn(['retreat_time']);
            $table->dropColumn(['need_check_retreat']);
        });
    }
}
