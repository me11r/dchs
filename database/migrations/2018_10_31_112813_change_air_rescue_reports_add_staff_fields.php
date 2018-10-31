<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAirRescueReportsAddStaffFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->integer('staff_head')->default(0)->nullable()->after('id');
            $table->integer('staff_total')->default(0)->nullable()->after('id');
            $table->integer('staff_action')->default(0)->nullable()->after('id');
            $table->integer('staff_duty_shift')->default(0)->nullable()->after('id');
        });

        Schema::dropIfExists('air_rescue_report_persons_items');

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
                'staff_head',
                'staff_total',
                'staff_action',
                'staff_duty_shift',
            ]);
        });

        Schema::create('air_rescue_report_persons_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')
                ->on('air_rescue_reports')
                ->onDelete('cascade');

            $table->unsignedInteger('staff_id');
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onDelete('cascade');

            $table->string('status');

            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }
}
