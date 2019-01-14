<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_statuses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->softDeletes();

            $table->timestamps();
        });

        $records = [
            ['name' => 'ТО'],
            ['name' => 'ТО-1'],
            ['name' => 'СО'],
        ];

        foreach ($records as $record) {
            \App\VehicleStatus::firstOrCreate($record);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_statuses');
    }
}
