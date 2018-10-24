<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperDutyShiftStaffItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oper_duty_shift_staff_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('shift_id');
            $table->foreign('shift_id')
                ->references('id')
                ->on('oper_duty_shifts')
                ->onDelete('cascade');

            $table->unsignedInteger('staff_id');
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onDelete('cascade');

            $table->string('rank')->nullable()->index();
            $table->date('date')->nullable()->index();

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
        Schema::dropIfExists('oper_duty_shift_staff_items');
    }
}
