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
            $table->integer('street_id')->unsigned();
            $table->integer('crossroad_1_id')->unsigned();
            $table->integer('crossroad_2_id')->unsigned();
            $table->integer('incident_type_id')->unsigned();
            $table->text('description')->nullable();
            $table->string('caller_phone');
            $table->string('caller_name');
            $table->time('call_time');
            $table->integer('additional_street_id')->unsigned();
            $table->integer('additional_incident_type_id')->unsigned();
            $table->text('measures');
            $table->text('resources');
            $table->integer('injured');
            $table->integer('dead')->nullable();
            $table->integer('evacuated');
            $table->integer('hospitalized');
            $table->timestamps();

            $table->foreign('street_id')->name('sis')->references('id')->on('streets');
            $table->foreign('crossroad_1_id')->name('c1is')->references('id')->on('streets');
            $table->foreign('crossroad_2_id')->name('c2is')->references('id')->on('streets');
            $table->foreign('incident_type_id')->name('itii')->references('id')->on('incident_types');
            $table->foreign('additional_street_id')->name('asis')->references('id')->on('streets');
            $table->foreign('additional_incident_type_id')->name('aitiit')
                ->references('id')->on('incident_types');
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
