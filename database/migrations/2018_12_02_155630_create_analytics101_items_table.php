<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalytics101ItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytics101_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('analytics_id');
            $table->foreign('analytics_id')
                ->references('id')
                ->on('analytics101s')
                ->onDelete('cascade');

            $table->longText('text')->nullable();

            $table->unsignedInteger('trip_result_id');
            $table->foreign('trip_result_id')
                ->references('id')
                ->on('dict_trip_result')
                ->onDelete('cascade');

            $table->unsignedInteger('ticket101_id');
            $table->foreign('ticket101_id')
                ->references('id')
                ->on('ticket101')
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
        Schema::dropIfExists('analytics101_items');
    }
}
