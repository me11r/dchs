<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('meds')) {
            Schema::create('meds', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->double('lat');
                $table->double('long');
            });
        }

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => MedsSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meds');
    }
}
