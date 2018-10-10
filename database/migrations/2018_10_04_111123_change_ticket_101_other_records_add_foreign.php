<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101OtherRecordsAddForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \DB::table('ticket101_other_records')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        Schema::table('ticket101_other_records', function (Blueprint $table) {
            $table->unsignedInteger('ticket101_id')->change();
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');
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
            $table->dropForeign(['ticket101_id']);
        });

        Schema::table('ticket101_other_records', function (Blueprint $table) {
            $table->integer('ticket101_id')->change();
        });
    }
}
