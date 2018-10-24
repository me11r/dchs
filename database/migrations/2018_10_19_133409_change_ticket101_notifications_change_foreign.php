<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101NotificationsChangeForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_notifications', function (Blueprint $table) {
            $db = new \App\Services\DbHelper();
            $db->truncate('ticket101_notifications');

            $table->dropForeign(['notification_service_id']);
            $table->foreign('notification_service_id')
                ->references('id')
                ->on('service_types')
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
            $table->dropForeign(['notification_service_id']);
            $table->foreign('notification_service_id')
                ->references('id')
                ->on('notification_services')
                ->onDelete('cascade');
        });
    }
}
