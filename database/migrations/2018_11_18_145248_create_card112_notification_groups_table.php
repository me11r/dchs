<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCard112NotificationGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card112_notification_groups', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('card_112_id');
            $table->foreign('card_112_id')
                ->references('id')
                ->on('card_112')
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
        Schema::dropIfExists('card112_notification_groups');
    }
}
