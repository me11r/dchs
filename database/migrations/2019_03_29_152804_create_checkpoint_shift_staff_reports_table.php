<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckpointShiftStaffReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkpoint_shift_staff_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('note')->nullable();
            $table->date('date')->index()->nullable();
            $table->unsignedInteger('shift_id');
            $table->foreign('shift_id')
                ->references('id')
                ->on('oper_duty_shifts')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkpoint_shift_staff_reports');
    }
}
