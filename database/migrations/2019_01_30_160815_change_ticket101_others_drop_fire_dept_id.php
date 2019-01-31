<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101OthersDropFireDeptId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->dropForeign(['fire_department_id']);
            $table->dropForeign(['staff_id']);
            $table->dropColumn(['fire_department_id', 'department','staff_id']);
            $table->string('responsible_person')->after('id')->nullable();

            $table->unsignedInteger('formation_report_id')->after('id')->nullable();
            $table->foreign('formation_report_id')
                ->references('id')
                ->on('formation_reports')
                ->onDelete('cascade');

            $table->unsignedInteger('final_ride_type_id')->after('id')->nullable();
            $table->foreign('final_ride_type_id')
                ->references('id')
                ->on('ride_types')
                ->onDelete('cascade');

            $table->string('final_responsible_person')->after('id')->nullable();
            $table->string('final_direction')->after('id')->nullable();
            $table->string('final_object_name')->after('id')->nullable();
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
            $table->unsignedInteger('fire_department_id')->after('id')->nullable();
            $table->string('department')->after('id')->nullable();
            $table->unsignedInteger('staff_id')->after('id')->nullable();

            $table->dropForeign(['final_ride_type_id']);
            $table->dropForeign(['formation_report_id']);
            $table->dropColumn(['final_ride_type_id']);
            $table->dropColumn(['formation_report_id']);
            $table->dropColumn(['final_responsible_person']);
            $table->dropColumn(['final_direction']);
            $table->dropColumn(['final_object_name']);

            $table->foreign('fire_department_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');

            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onDelete('cascade');


        });
    }
}
