<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDictionariesAddCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('dictionaries', function (Blueprint $table) {
//            $table->unsignedInteger('dictionary_category_id')->after('id')->nullable();
//            $table->foreign('dictionary_category_id')
//                ->references('id')
//                ->on('dictionary_categories')
//                ->onDelete('SET NULL');
//
//            $table->integer('sort_order')->index()->after('id')->nullable()->default(10);
//            $table->string('url')->after('id')->nullable();
//
//            $table->softDeletes()->after('updated_at');
//        });

        $dictionaryToRename = \App\Dictionary::name('Нормативно-справочная информация')->first();
        $dictionaryToRename->title = 'События в пути';
        $dictionaryToRename->save();

        $dictionaryToRename = \App\Dictionary::name('ЛС - Страший мастер связи')->first();
        $dictionaryToRename->title = 'ЛС - Старший мастер связи';
        $dictionaryToRename->save();

        $setCategoryItems = [
            ['category' => '101', 'dict' => 'Объект возгорания'],
            ['category' => '101', 'dict' => 'Класс пожара'],
            ['category' => '101', 'dict' => 'Способ ликвидации'],
            ['category' => '101', 'dict' => 'Объект горения'],
            ['category' => '101', 'dict' => 'Причина выезда'],
            ['category' => '101', 'dict' => 'Источник противопожарного водоснабжения'],
            ['category' => '101', 'dict' => 'Типы выездов'],
            ['category' => '101', 'dict' => 'Тип жилого сектора'],
            ['category' => '101', 'dict' => 'Номер караула'],
            ['category' => '101', 'dict' => 'Статус А/М'],
            ['category' => '101', 'dict' => 'Номер норматива'],
            ['category' => '101', 'dict' => 'Тип норматива'],
            ['category' => '101', 'dict' => 'Классификация объектов'],
            ['category' => '101', 'dict' => 'Типы учений'],
            ['category' => '101', 'dict' => 'Стволы', 'url' => ''],
            ['category' => '101', 'dict' => 'Типы стволов'],
            ['category' => '101', 'dict' => 'Виды техники'],
            ['category' => '101', 'dict' => 'Пожарные части', 'url' => '/dictionaries/fire-departments'],
            ['category' => '101', 'dict' => 'Опер планы', 'url' => '/dictionaries/operational-plans'],
            ['category' => '101', 'dict' => 'Опер карточки', 'url' => '/dictionaries/operational-cards'],
            ['category' => '101', 'dict' => 'События в пути'],

            ['category' => '112', 'dict' => 'Тип происшествия'],
            ['category' => '112', 'dict' => 'Название ЧС'],
            ['category' => '112', 'dict' => 'Место подтопления'],
            ['category' => '112', 'dict' => 'Тип схода снежных лавин'],
            ['category' => '112', 'dict' => 'Происшествия на лифтах'],
            ['category' => '112', 'dict' => 'Типы заболеваний'],
            ['category' => '112', 'dict' => 'Типы инцидентов', 'url' => '/dictionaries/incident-types'],
            ['category' => '112', 'dict' => 'Моренные озера'],
            ['category' => '112', 'dict' => 'Типы воздушных судов', 'url' => '/dictionaries/aircraft-types'],
            ['category' => '112', 'dict' => 'Воздушные суда', 'url' => '/dictionaries/aircrafts'],

            ['category' => 'Личный состав 101', 'dict' => 'ЛС - ЦППС'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - ИПЛ'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - Старший мастер связи'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - Водоканал'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - ЦРБ'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - База ГДЗС'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - Врач'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - Оперативные дежурные автомашины'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - КШМ'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - ДСПТ'],
            ['category' => 'Личный состав 101', 'dict' => 'ЛС - ИПЛ "Жалын"'],

            ['category' => 'Личный состав 112', 'dict' => 'ЛС - ЕДДС'],
            ['category' => 'Личный состав 112', 'dict' => 'ЛС ОДС'],
            ['category' => 'Личный состав 112', 'dict' => 'Ответственные по районам', 'url' => '/dictionaries/district-managers'],

            ['category' => 'Общий раздел', 'dict' => 'Персоны суточного отчета', 'url' => '/dictionaries/daily-report-persons'],
            ['category' => 'Общий раздел', 'dict' => 'Оперативные группы (ОГ)'],
            ['category' => 'Общий раздел', 'dict' => 'Группы личного состава', 'url' => '/notification-groups'],
        ];

        foreach ($setCategoryItems as $sortOrder => $setCategoryItem) {

            $category = \App\DictionaryCategory::name($setCategoryItem['category'])->first();
            $dict = \App\Dictionary::name($setCategoryItem['dict'])->first();

            if ($category && $dict) {
                $dict->dictionary_category_id = $category->id;
                $dict->sort_order = ++$sortOrder;
                $dict->save();
            }
            else {
                echo "{$setCategoryItem['category']}: {$setCategoryItem['dict']} not found \n\r";
                \App\Dictionary::firstOrCreate([
                    'dictionary_category_id' => $category->id,
                    'sort_order' => ++$sortOrder,
                    'url' => $setCategoryItem['url'] ?? null,
                    'title' => $setCategoryItem['dict'],
                    'table' => '',
                    'model' => '',
                ]);
            }
        }

        $commonSection = \App\DictionaryCategory::name('Общий раздел')->first();

        $otherDicts = \App\Dictionary::whereNull('dictionary_category_id')->update(['dictionary_category_id' => $commonSection->id]);

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => RightsSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictionaries', function (Blueprint $table) {

            $table->dropForeign(['dictionary_category_id']);
            $table->dropIndex(['sort_order']);

            $table->dropColumn([
                'dictionary_category_id',
                'sort_order',
                'url',
            ]);

            $table->dropSoftDeletes();
        });

        $dictionaryToRename = \App\Dictionary::name('События в пути')->first();
        $dictionaryToRename->title = 'Нормативно-справочная информация';
        $dictionaryToRename->save();
    }
}
