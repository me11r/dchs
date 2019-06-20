<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101OthersAddFireDepartmentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->unsignedInteger('fire_department_id')->after('id')->nullable();
            $table->foreign('fire_department_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->dropForeign(['fire_department_id']);
            $table->dropColumn(['fire_department_id']);
        });
    }
}
