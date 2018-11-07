<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->timestamps();
        });

        $data = [
            ['name' => 'светофор',],
            ['name' => 'автозатор',],
            ['name' => 'ДТП',],
            ['name' => 'отказ техники',],
            ['name' => 'узкие проезды',],
            ['name' => 'прочее',],
        ];

        foreach ($data as $datum) {
            \App\EventInfo::firstOrCreate($datum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_infos');
    }
}
