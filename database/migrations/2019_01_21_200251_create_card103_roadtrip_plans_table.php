<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCard103RoadtripPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card103_roadtrip_plans', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('card103_id')->nullable();
            $table->string('department_id')->nullable();

            $table->timestamp('dispatch_time')->nullable();
            $table->timestamp('accept_time')->nullable();
            $table->timestamp('out_time')->nullable();
            $table->timestamp('arrive_time')->nullable();
            $table->timestamp('loc_time')->nullable();
            $table->timestamp('liqv_time')->nullable();
            $table->timestamp('ret_time')->nullable();
            $table->boolean('dispatched')->nullable();
            $table->boolean('printed')->nullable();

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
        Schema::dropIfExists('card103_roadtrip_plans');
    }
}
