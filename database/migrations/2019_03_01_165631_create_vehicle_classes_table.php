<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->softDeletes();
            $table->timestamps();
        });

        $newData = [
            ['name' => 'АЦ'],
            ['name' => 'АКТП'],
            ['name' => 'АЛ'],
            ['name' => 'АБР'],
        ];

        foreach ($newData as $newDatum) {
            \App\VehicleClass::create($newDatum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_classes');
    }
}
