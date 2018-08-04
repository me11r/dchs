<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quakes', function (Blueprint $table) {
            $table->increments('id');

            $table->text('description');
            $table->datetime('date_almaty');
            $table->datetime('date_greenwich');
            $table->string('epicenter');
            $table->float('energy_class');
            $table->float('mpv');
            $table->string('coordinates');
            $table->float('deep');
            $table->string('information');

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
        Schema::dropIfExists('quakes');
    }
}
