<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSummaryFieldsToTheTicket101Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->string('out_number')->nullable();
            $table->text('special_tech')->nullable();
            $table->text('ticket_result')->nullable();

            $table->integer('file_1_id')->comment('Объяснения очевидца')->nullable();
            $table->integer('file_2_id')->comment('Схема пожара')->nullable();
            $table->integer('file_3_id')->comment('Информация от начальника караула')->nullable();
            $table->integer('file_4_id')->comment('Информация от РОЧС')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->dropColumn([
                'out_number',
                'special_tech',
                'ticket_result',
                'file_1_id',
                'file_2_id',
                'file_3_id',
                'file_4_id'
            ]);
        });
    }
}
