<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmergencyNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_names', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->softDeletes();
            $table->timestamps();
        });

        $records = [
            ['name' => 'Пожар',],
            ['name' => 'Авиационный',],
        ];

        foreach ($records as $record) {
            \App\EmergencyName::create($record);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emergency_names');
    }
}
