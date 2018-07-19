<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Card112 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_112', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('address_id')->unsigned();
            $table->integer('crossroad_1_id')->unsigned();
            $table->integer('crossroad_2_id')->unsigned();
            $table->integer('incident_type_id')->unsigned();
            $table->text('description')->nullable();
            $table->string('caller_phone');
            $table->string('caller_name');
            $table->time('call_time');
            $table->integer('additional_address_id')->unsigned();
            $table->integer('additional_incident_type_id')->unsigned();
            $table->text('measures');
            $table->text('resources');
            $table->integer('injured');
            $table->integer('dead')->nullable();
            $table->integer('evacuated');
            $table->integer('hospitalized');
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('addresses');
            $table->foreign('crossroad_1_id')->references('id')->on('addresses');
            $table->foreign('crossroad_2_id')->references('id')->on('addresses');
            $table->foreign('incident_type_id')->references('id')->on('incident_types');
            $table->foreign('additional_address_id')->references('id')->on('addresses');
            $table->foreign('additional_incident_type_id')->references('id')->on('incident_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_112');
    }
}
