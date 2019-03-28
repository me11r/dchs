<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index();
            $table->boolean('reserved')->index()->default(false)->comment('ЗПУ-запасной пункт управления');
            $table->integer('sort_order')->index()->default(20);

            $table->softDeletes();

            $table->timestamps();
        });

        $main = [
            'Алматинская',
            'Актюбинск',
            'Каскелен',
            'ВКО',
            'Атырау',
            'Тараз',
            'Жезказган',
            'Балхаш',
            'Караганда',
            'Каратон',
            'Кызыл-орда',
            'Кокшетау',
            'Костанай',
            'ЦУКС',
            'Павлодар',
            'СКО',
            'Талдыкорган',
            'Мангыстау',
            'ЗКО',
            'Астана',
            'ЮКО',
        ];

        $reserved = [
            'Актюбинск',
            'Каскелен',
            'ВКО',
            'Атырау',
            'Тараз',
            'Балхаш',
            'Караганда',
            'Узын-агач',
            'Кызыл-орда',
            'Кокшетау',
            'Костанай',
            'Павлодар',
            'СКО',
            'Мангыстау',
            'ЗКО',
            'Астана',
            'ЮКО',
        ];

        foreach ($main as $key => $item) {
            \App\Direction::create([
                'name' => $item,
                'sort_order' => ++$key,
                'reserved' => false,
            ]);
        }

        foreach ($reserved as $key => $item) {
            \App\Direction::create([
                'name' => $item,
                'sort_order' => ++$key,
                'reserved' => true,
            ]);
        }

        $dictCategory = \App\DictionaryCategory::name('Общий раздел')->first();

        if($dictCategory) {
            \App\Dictionary::create([
                'table' => 'directions',
                'title' => 'Направления',
                'url' => '/dictionaries/directions',
                'dictionary_category_id' => $dictCategory->id,
                'sort_order' => 10,
                'model' => 'App\Direction'
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
        \App\Dictionary::name('Направления')->forceDelete();
        Schema::dropIfExists('directions');
    }
}
