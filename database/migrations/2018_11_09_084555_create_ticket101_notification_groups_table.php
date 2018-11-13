<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicket101NotificationGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket101_notification_groups', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ticket101_id');
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->unsignedInteger('notification_group_id');
            $table->foreign('notification_group_id')
                ->references('id')
                ->on('notification_groups')
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
        Schema::dropIfExists('ticket101_notification_groups');
    }
}
