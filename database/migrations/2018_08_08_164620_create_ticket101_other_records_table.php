<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicket101OtherRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket101_other_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket101_id')->unsighned();
            $table->time('time');
            $table->string('comment', 1000);
            $table->integer('trunk_id')->unsighned();
            $table->integer('count');
            $table->double('square');

            $table->foreign('ticket101_id')
                ->name('ticket101')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->foreign('trunk_id')
                ->name('trunk')
                ->references('id')
                ->on('dict_trunk')
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
        Schema::table('ticket101_other_records', function (Blueprint $table) {
            $table->dropForeign('ticket101');
            $table->dropForeign('trunk');
        });
        Schema::dropIfExists('ticket101_other_records');
    }
}
