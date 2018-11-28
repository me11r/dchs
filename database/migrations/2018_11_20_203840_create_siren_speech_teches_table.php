<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSirenSpeechTechesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*СРУ (сиренно-речевые установки)*/
        Schema::create('siren_speech_teches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sst')->nullable()->default(0)->comment('СРУ');
            $table->integer('motor')->nullable()->default(0)->comment('моторные');
            $table->integer('demounted')->nullable()->default(0)->comment('демонтированные');
            $table->integer('broken')->nullable()->default(0)->comment('в не рабочем состоянии');
            $table->integer('inactive')->nullable()->default(0)->comment('не активные');
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
        Schema::dropIfExists('siren_speech_teches');
    }
}
