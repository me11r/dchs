<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckpointShiftStaffItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkpoint_shift_staff_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('staff_id');
            $table->foreign('staff_id')
                ->references('id')
                ->on('checkpoint_shift_staffs')
                ->onDelete('cascade');

            $table->string('rank')->nullable()->index();
            $table->date('date')->nullable();
            $table->string('inactive_type')->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->longText('comment')->nullable();

            $table->unsignedInteger('report_id');
            $table->foreign('report_id')
                ->references('id')
                ->on('checkpoint_shift_staff_reports')
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
        Schema::dropIfExists('checkpoint_shift_staff_items');
    }
}
