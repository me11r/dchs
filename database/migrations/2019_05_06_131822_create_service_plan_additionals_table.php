<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicePlanAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_plan_additionals', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('date_time');
            $table->unsignedInteger('saved_animals')->nullable();
            $table->unsignedInteger('poisoned')->nullable();
            $table->unsignedInteger('wounded')->nullable();
            $table->longText('description')->nullable();
            $table->text('location')->nullable();
            $table->unsignedInteger('died')->nullable();
            $table->unsignedInteger('injured')->nullable();
            $table->unsignedInteger('hospitalized')->nullable();
            $table->unsignedInteger('evacuated')->nullable();
            $table->unsignedInteger('saved')->nullable();

            $table->unsignedInteger('service_plan_id')->nullable();
            $table->foreign('service_plan_id')
                ->references('id')
                ->on('ticket101_service_plans')
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
        Schema::dropIfExists('service_plan_additionals');
    }
}
