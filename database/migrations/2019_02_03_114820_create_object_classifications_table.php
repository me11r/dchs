<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('object_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });

        $data = [
            ['name' => 'Объекты со СДЯВ'],
            ['name' => 'Объекты жизнеобеспечения'],
            ['name' => 'Здания повышенной этажности'],
            ['name' => 'Административно-общественные здания'],
            ['name' => 'Объекты производственного назначения'],
            ['name' => 'Объекты торговли'],
            ['name' => 'Учебные и детские учреждения'],
            ['name' => 'Культурно-зрелищные учреждения'],
            ['name' => 'Лечебно-профилактические учреждения'],
            ['name' => 'Прочие объекты'],
        ];

        foreach ($data as $datum) {
            \App\ObjectClassification::create($datum);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('object_classifications');
    }
}
