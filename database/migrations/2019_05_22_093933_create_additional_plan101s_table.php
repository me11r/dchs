<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalPlan101sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_plan101s', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('ticket101_id');
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->unsignedInteger('special_plan_id')->nullable();
            $table->foreign('special_plan_id')
                ->references('id')
                ->on('special_plans')
                ->onDelete('cascade');

            $table->unsignedInteger('operational_card_id')->nullable();
            $table->foreign('operational_card_id')
                ->references('id')
                ->on('operational_cards')
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
        Schema::dropIfExists('additional_plan101s');
    }
}
