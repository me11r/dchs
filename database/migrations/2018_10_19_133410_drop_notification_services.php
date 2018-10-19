<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropNotificationServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_services', function (Blueprint $table) {
            Schema::dropIfExists('notification_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('notification_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code');

            $table->unsignedInteger('head_user_id')->nullable();
            $table->foreign('head_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }
}
