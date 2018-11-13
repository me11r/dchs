<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRideTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ride_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        $names = [
            ['name' => 'Корректировка'],
            ['name' => 'ПСП (пожарно-строевая подготовка)'],
            ['name' => 'ТДК (теплодымокамера)'],
            ['name' => 'КШУ/РКШУ (командно- штабное учение/ республиканское командно-штабное учение)'],
            ['name' => 'Учения'],
            ['name' => 'Расстановка'],
            ['name' => 'АЗС (автозаправочная станция)'],
            ['name' => 'ТО (техническое обслуживание)'],
            ['name' => 'ГДЗС (газодымозащитная служба)'],
            ['name' => 'РБ (рукавная база)'],
            ['name' => 'Хоз. работы'],
            ['name' => 'По району'],
            ['name' => 'ТСУ (тактико-специальные учения)'],
        ];


        foreach ($names as $name) {
            \App\RideType::firstOrCreate($name);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ride_types');
    }
}
