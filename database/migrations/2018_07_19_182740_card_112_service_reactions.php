<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Card112ServiceReactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_112_service_reactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('card112_id')->unsigned();
            $table->integer('service_type_id')->unsigned();
            $table->time('message_time');
            $table->string('name');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->timestamps();

            $table->foreign('card112_id')->references('id')->on('card_112')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_112_service_reactions');
    }
}
