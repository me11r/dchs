<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOperDutyShiftStaffItemsAddReportId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oper_duty_shift_staff_items', function (Blueprint $table) {
            $table->unsignedInteger('report_id')->after('id')->nullable();
            $table->foreign('report_id')
                ->references('id')
                ->on('oper_duty_shift_staff_reports')
                ->onDelete('cascade');

        });

        foreach (\App\OperDutyShiftStaffItem::all() as $item) {
            $report = \App\OperDutyShiftStaffReport::firstOrCreate([
                'date' => $item->date,
                'shift_id' => $item->shift_id,
            ]);

            if($report) {
                $item->report_id = $report->id;
                $item->save();
            }
        }

        Schema::table('oper_duty_shift_staff_items', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn(['shift_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oper_duty_shift_staff_items', function (Blueprint $table) {
            $table->unsignedInteger('shift_id')->after('id')->nullable();
            $table->foreign('shift_id')
                ->references('id')
                ->on('oper_duty_shifts')
                ->onDelete('cascade');
        });

        foreach (\App\OperDutyShiftStaffItem::all() as $item) {
            if($report = $item->report) {
                $item->shift_id = $report->shift_id;
                $item->save();
            }
        }

        Schema::table('oper_duty_shift_staff_items', function (Blueprint $table) {
            $table->dropForeign(['report_id']);
            $table->dropColumn(['report_id']);
        });
    }
}
