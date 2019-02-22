<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloodingPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flooding_places', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();
            $table->softDeletes();

            $table->timestamps();
        });

        $newData = [
            ['name' => 'ЧЖД'],
            ['name' => 'двор ЧЖД'],
            ['name' => 'улица'],
        ];

        foreach ($newData as $newDatum) {
            \App\FloodingPlace::create($newDatum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flooding_places');
    }
}
