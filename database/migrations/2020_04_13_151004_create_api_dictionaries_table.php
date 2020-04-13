<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiDictionariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_dictionaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->unsignedInteger('service_type_id')->nullable();
            $table->foreign('service_type_id')
                ->references('id')
                ->on('service_types')
                ->onDelete('cascade');
            $table->longText('data');

            $table->timestamps();
        });

        $newRecords = [
            'service_type' => 'Служба спасения-109 г. Алматы',
            'data' => [
                [
                    'category' => 'Поисковые работы',
                    'subcategories' => [
                        'Поиск пострадавших в высокогорье',
                        'Поиск пострадавших в лавинах',
                        'Поиск пострадавших на водных акваториях (река, озеро, каналах)',
                        'Поиск пострадавших в лесных районах предгорья',
                        'Поиск пострадавших в завалах зданий, мжд и т.д.',
                        'Поиск утонувших на реках и водоемах',
                    ]
                ],
                [
                    'category' => 'Несчастный случай',
                    'subcategories' => [
                        'Извлечение из труднодоступных мест (открытый люк, канализации, арыков и т.д.)',
                        'Падение с высоты зданий и мостовых переходов и т.д.',
                        'Человек в завале',
                        'Человек в колодце, в траншее',
                    ]
                ],
                [
                    'category' => 'Блокирование людей',
                    'subcategories' => [
                        'Больной в помещении',
                        'Люди в помещении',
                        'Пожилой человек в помещении',
                        'Ребенок в помещении',
                    ]
                ],
                [
                    'category' => 'Транспортные аварии',
                    'subcategories' => [
                        'Аварии на автодорогах (ДТП)',
                        'Аварии на водном транспорте',
                        'Аварии в общественном транспорте',
                        'Аварии на железнодорожном транспорте',
                        'Авиакатастрофы и инциденты',
                    ]
                ],
                [
                    'category' => 'Подтопления',
                    'subcategories' => [
                        'Откачка воды в подвальных помещений',
                        'Откачка воды на территории зданий социальной инфраструктуры (д/с, школы, подземные переходы и т.д.)',
                    ]
                ],
                [
                    'category' => 'Ликвидация последствий от падения деревьев',
                    'subcategories' => [
                        'Падение дерева на автомашину',
                        'Падение дерева на детскую площадку',
                        'Падение дерева на магистральные трубопроводы',
                        'Падение дерева на здания',
                        'Падение дерева на людей',
                    ]
                ]
            ]
        ];

        $serviceType = \App\Models\ServiceType::where('name', $newRecords['service_type'])->first();
        if ($serviceType) {
            \App\ApiDictionary::firstOrCreate([
                'name' => 'Классификатор услуг экстренного характера',
                'service_type_id' => $serviceType->id,
                'data' => $newRecords['data']
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_dictionaries');
    }
}
