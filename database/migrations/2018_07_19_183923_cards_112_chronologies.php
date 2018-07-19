<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cards112Chronologies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards_112_chronologies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cards_112_id')->unsigned();
            $table->time('time');
            $table->text('comment');
            $table->text('additional_comment');
            $table->timestamps();

            $table->foreign('cards_112_id')->references('id')->on('card_112')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards_112_chronologies');
    }
}
