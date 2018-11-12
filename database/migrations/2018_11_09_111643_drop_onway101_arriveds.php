<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropOnway101Arriveds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('on_way101s');
        Schema::dropIfExists('arrived101s');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('on_way101s', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ticket101_id');
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->unsignedInteger('event_info_id');
            $table->foreign('event_info_id')
                ->references('id')
                ->on('event_infos')
                ->onDelete('cascade');

            $table->unsignedInteger('fire_department_result_id');
            $table->foreign('fire_department_result_id')
                ->references('id')
                ->on('fire_department_results')
                ->onDelete('cascade');

            $table->time('time')->nullable();
            $table->text('information')->nullable();

            $table->timestamps();
        });

        Schema::create('arrived101s', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ticket101_id');
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->unsignedInteger('event_info_arrived_id');
            $table->foreign('event_info_arrived_id')
                ->references('id')
                ->on('event_info_arriveds')
                ->onDelete('cascade');

            $table->unsignedInteger('fire_department_result_id');
            $table->foreign('fire_department_result_id')
                ->references('id')
                ->on('fire_department_results')
                ->onDelete('cascade');

            $table->integer('quantity')->nullable();
            $table->integer('working_time')->nullable();

            $table->text('information')->nullable();


            $table->timestamps();
        });
    }
}
