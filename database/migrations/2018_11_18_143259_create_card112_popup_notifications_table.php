<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCard112PopupNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card112_popup_notifications', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('card_112_id');
            $table->foreign('card_112_id')
                ->references('id')
                ->on('card_112')
                ->onDelete('cascade');

            $table->unsignedInteger('notification_id');
            $table->foreign('notification_id')
                ->references('id')
                ->on('notifications')
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
        Schema::dropIfExists('card112_popup_notifications');
    }
}
