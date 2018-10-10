<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicket101ServicePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket101_service_plans', function (Blueprint $table) {
            $table->increments('id');

            $table->string('department');

            $table->unsignedInteger('card_id')->nullable();
            $table->foreign('card_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->timestamp('return_time')->nullable();
            $table->timestamp('arrive_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->boolean('is_accepted')->default(false);

            $table->string('name_accepted')->nullable();


            $table->softDeletes();


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
        Schema::dropIfExists('ticket101_service_plans');
    }
}
