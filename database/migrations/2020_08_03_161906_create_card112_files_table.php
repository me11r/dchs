<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCard112FilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card112_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('card_id');
            $table->unsignedInteger('file_id');

            $table->foreign('card_id')
                ->references('id')
                ->on('card_112')
                ->onDelete('cascade');

            $table->foreign('file_id')
                ->references('id')
                ->on('file_uploads')
                ->onDelete('cascade');

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
        Schema::dropIfExists('card112_files');
    }
}
