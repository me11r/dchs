<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSirenSpeechTechItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siren_speech_tech_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('tech_id');
            $table->foreign('tech_id')
                ->references('id')
                ->on('siren_speech_teches')
                ->onDelete('cascade');

            $table->text('text')->nullable();
            $table->string('type'); //demounted | broken | inactive

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
        Schema::dropIfExists('siren_speech_tech_items');
    }
}
