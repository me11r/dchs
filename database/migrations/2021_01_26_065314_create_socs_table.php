<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->double('lat');
            $table->double('long');
        });

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => SocsSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socs');
    }
}
