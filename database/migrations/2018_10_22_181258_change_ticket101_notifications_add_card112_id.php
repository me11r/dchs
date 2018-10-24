<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101NotificationsAddCard112Id extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_notifications', function (Blueprint $table) {
            $table->unsignedInteger('ticket112_id')->nullable()->after('ticket101_id');
            $table->foreign('ticket112_id')
                ->references('id')
                ->on('card_112')
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
        Schema::table('ticket101_notifications', function (Blueprint $table) {
            $table->dropForeign(['ticket112_id']);
            $table->dropColumn(['ticket112_id']);
        });
    }
}
