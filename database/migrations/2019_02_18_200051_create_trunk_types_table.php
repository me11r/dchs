<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrunkTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trunk_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();

            $table->softDeletes();
            $table->timestamps();
        });

        $types = [
            ['name' => 'Пенный'],
            ['name' => 'Водяной'],
        ];

        foreach ($types as $type) {
            \App\TrunkType::create($type);
        }

        $trunkDict = \App\Dictionary::where('table','dict_trunk')->delete();
        $trunkDict = \App\Dictionary::where('table','event_info_arriveds')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trunk_types');
        $trunkDict = \App\Dictionary::create([
            'title' => 'Стволы',
            'table' => 'dict_trunk',
            'model' => \App\Models\Trunk::class
        ]);

        $trunkDict = \App\Dictionary::create([
            'title' => 'Нормативно-справочная информация: на месте',
            'table' => 'event_info_arriveds',
            'model' => \App\EventInfoArrived::class
        ]);

    }
}
