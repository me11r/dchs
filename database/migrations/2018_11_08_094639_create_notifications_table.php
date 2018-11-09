<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', 500);
            $table->string('body', 1000);
            $table->timestamp('send_date')->nullable();
            $table->timestamp('receive_date')->nullable();

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unsignedInteger('notification_status_id')->default(\App\Enums\NotificationStatusType::CREATED);
            $table->foreign('notification_status_id')
                ->references('id')
                ->on('notification_statuses')
                ->onDelete('cascade');

            $table->unsignedInteger('notification_group_id')->nullable();
            $table->foreign('notification_group_id')
                ->references('id')
                ->on('notification_groups')
                ->onDelete('cascade');

            $table->string('response', 1000)->nullable();

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
        Schema::dropIfExists('notifications');
    }
}
