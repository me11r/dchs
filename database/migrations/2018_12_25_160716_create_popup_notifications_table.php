<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopupNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popup_notifications', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('sender_id')->nullable();
            $table->foreign('sender_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedInteger('receiver_id')->nullable();
            $table->foreign('receiver_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->longText('message');
            $table->text('url')->nullable();
            $table->boolean('is_viewed')->nullable()->default(false);
            $table->string('popup_position')->nullable();
            $table->string('popup_type')->nullable();

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
        Schema::dropIfExists('popup_notifications');
    }
}
