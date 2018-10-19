<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentResultsAddAcceptTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->timestamp('accept_time')->after('dispatch_id')->nullable();
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
            $table->dropColumn(['accept_time']);
        });
    }
}
