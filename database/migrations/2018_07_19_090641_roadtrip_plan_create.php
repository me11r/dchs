<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoadtripPlanCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roadtrip_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id')->index();
            $table->integer('card_id')->index();
            $table->dateTime('return_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('roadtrip_plan');
    }
}
