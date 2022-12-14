<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeVehiclesAddVehicleClassId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->unsignedInteger('vehicle_class_id')->nullable()->after('id');
            $table->softDeletes();
            $table->foreign('vehicle_class_id')
                ->references('id')
                ->on('vehicle_classes')
                ->onDelete('cascade');
        });

        $newData = [
            'АЦ',
            'АКТП',
            'АЛ',
            'АБР',
        ];

        foreach ($newData as $newDatum) {
            $vehicles = \App\Models\Vehicle::where('name', 'like', "%$newDatum%")->get();
            foreach ($vehicles as $vehicle) {
                $class = \App\VehicleClass::where('name', $newDatum)->first();

                if($class) {
                    $vehicle->vehicle_class_id = $class->id;
                    $vehicle->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['vehicle_class_id']);
            $table->dropColumn(['vehicle_class_id']);
        });
    }
}
