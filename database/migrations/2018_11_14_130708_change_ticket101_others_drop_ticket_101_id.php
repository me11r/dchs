<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101OthersDropTicket101Id extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->dropForeign(['ticket_101_id']);
            $table->dropColumn(['ticket_101_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101_others', function (Blueprint $table) {
            $table->unsignedInteger('ticket_101_id')->after('id')->nullable();
            $table->foreign('ticket_101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');
        });
    }
}
