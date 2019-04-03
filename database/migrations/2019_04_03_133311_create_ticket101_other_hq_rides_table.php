<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicket101OtherHqRidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket101_other_hq_rides', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket101_id');
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101_others')
                ->onDelete('cascade');
            $table->integer('staff_count')->nullable();
            $table->integer('distance')->comment('Расстояние до места')->nullable();
            $table->boolean('dispatched')->nullable();
            $table->string('name')->nullable();
            $table->string('department')->nullable();
            $table->string('accept_time')->nullable();
            $table->string('out_time')->nullable();
            $table->string('arrive_time')->nullable();
            $table->string('retreat_time')->nullable();
            $table->string('ret_time')->nullable();
            $table->string('dispatch_time')->nullable();

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
        Schema::dropIfExists('ticket101_other_hq_rides');
    }
}
