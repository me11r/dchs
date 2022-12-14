<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventInfoArrivedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_info_arriveds', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->string('name')->index();

            $table->timestamps();
        });

        $data = [
            ['name' => 'ствол',],
            ['name' => 'ГПС',],
            ['name' => 'прочее',],
        ];

        foreach ($data as $datum) {
            \App\EventInfoArrived::firstOrCreate($datum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_info_arriveds');
    }
}
