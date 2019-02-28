<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElevatorEmergencyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elevator_emergency_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();

            $table->softDeletes();
            $table->timestamps();
        });

        $newData = [
            ['name' => 'при строительстве'],
            ['name' => 'при эксплуатации'],
        ];

        foreach ($newData as $newDatum) {
            \App\ElevatorEmergencyType::create($newDatum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elevator_emergency_types');
    }
}
