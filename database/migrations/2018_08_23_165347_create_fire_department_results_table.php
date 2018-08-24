<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFireDepartmentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fire_department_results', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ticket101_id');

            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
                ->onDelete('cascade');

            $table->unsignedInteger('fire_department_id')->nullable();
            $table->foreign('fire_department_id')
                ->references('id')
                ->on('fire_departments')
                ->onDelete('cascade');

            $table->unsignedInteger('dispatch_id')->nullable();
            $table->foreign('dispatch_id')
                ->references('id')
                ->on('roadtrip_plan')
                ->onDelete('cascade');

            $table->time('out_time')->nullable();
            $table->time('arrive_time')->nullable();
            $table->time('loc_time')->nullable();
            $table->time('liqv_time')->nullable();
            $table->time('ret_time')->nullable();
            $table->boolean('dispatched')->default(false)->nullable();
            $table->string('departments')->nullable();

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
        Schema::dropIfExists('fire_department_results');
    }
}
