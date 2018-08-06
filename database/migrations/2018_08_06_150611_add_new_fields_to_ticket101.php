<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToTicket101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->string('object_name');

            $table->integer('operational_plan_id')->unsigned();
            $table->foreign('operational_plan_id')->name('opidop')
                ->references('id')->on('dict_operational_plan')->onDelete('cascade');

            $table->integer('fire_department_id')->unsigned();
            $table->foreign('fire_department_id')->name('fdi')
                ->references('id')->on('fire_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->dropColumn('object_name');

            $table->dropForeign('opidop');
            $table->dropColumn('operational_plan_id');

            $table->dropForeign('fdi');
            $table->dropColumn('fire_department_id');
        });
    }
}
