<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_places', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();

            $table->softDeletes();
            $table->timestamps();
        });

        $data = [
            ['name' => 'автомашина',],
            ['name' => 'столб газовый',],
            ['name' => 'линии электропередач',],
            ['name' => 'забор',],
            ['name' => 'крыша дома',],
            ['name' => 'проезжая часть',],
        ];

        foreach ($data as $datum) {
            \App\IncidentPlace::firstOrCreate($datum);
        }

        $category = \App\DictionaryCategory::name('112')->first();

        $dictionary = \App\Dictionary::firstOrCreate([
            'table' => 'incident_places',
            'title' => 'Место происшествия',
            'dictionary_category_id' => $category->id ?? null, // 112
            'sort_order' => 10,
            'model' => \App\IncidentPlace::class
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incident_places');
    }
}
