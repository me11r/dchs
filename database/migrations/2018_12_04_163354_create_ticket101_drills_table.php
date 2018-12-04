<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicket101DrillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket101_drills', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('fire_department_id');
            $table->foreign('fire_department_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');

            $table->integer('department')->nullable();

            $table->time('time_begin')->nullable();

            $table->unsignedInteger('ride_type_id');
            $table->foreign('ride_type_id')
                ->references('id')
                ->on('ride_types')
                ->onDelete('cascade');


            $table->string('object_name')->nullable();

            $table->unsignedInteger('staff_id');
            $table->foreign('staff_id')
                ->references('id')
                ->on('staff')
                ->onDelete('cascade');

            $table->time('time_end')->nullable();

            $table->longText('note')->nullable();

            $table->text('direction')->nullable();

            $table->string('checked_pg')->nullable();
            $table->string('checked_pv')->nullable();
            $table->string('broken_pg')->nullable();
            $table->string('broken_pv')->nullable();
            $table->string('corrected_op')->nullable();
            $table->string('corrected_ok')->nullable();

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
        Schema::dropIfExists('ticket101_drills');
    }
}
