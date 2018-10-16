<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicket101NotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket101_notifications', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('notification_service_id')->nullable();
            $table->foreign('notification_service_id')
                ->references('id')
                ->on('notification_services')
                ->onDelete('cascade');

            $table->unsignedInteger('ticket101_id')->change();
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->time('message_time')->nullable();
            $table->string('name')->nullable();
            $table->time('arrive_time')->nullable();
            $table->boolean('checked')->default(false);

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
        Schema::dropIfExists('ticket101_notifications');
    }
}
