<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('techs', function (Blueprint $table) {
            $table->increments('id');

            $table->json('points');
            $table->string('title');
            $table->string('line_color');
            $table->string('fill_color');
            $table->double('opacity');

            $table->timestamps();
        });

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => TechsSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('techs');
    }
}
