<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessengerMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messenger_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id')->index();
            $table->integer('reciever_id')->index();
            $table->enum('message_type', ['text', 'file'])->default('text');
            $table->integer('file_id')->nullable();
            $table->text('message')->nullable();
            $table->boolean('is_viewed')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['sender_id', 'reciever_id']);
            $table->index(['reciever_id', 'is_viewed']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messenger_messages');
    }
}
