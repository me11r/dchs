<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationalGroupSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operational_group_schedules', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('group_id');
            $table->foreign('group_id')
                ->references('id')
                ->on('operational_groups')
                ->onDelete('cascade');
            $table->timestamp('date_begin');
            $table->timestamp('date_end');

            $table->timestamps();
        });

        \App\OperationalGroupSchedule::create([
            'group_id' => 2,
            'date_begin' => \Carbon\Carbon::parse('2018-12-25 18:00:00'),
            'date_end' => \Carbon\Carbon::parse('2018-12-26 18:00:00'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operational_group_schedules');
    }
}
