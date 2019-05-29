<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCivilProtectionServiceBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('civil_protection_service_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->unsignedInteger('sort_order')->index()->default(10);
            $table->softDeletes();
            $table->timestamps();
        });

        $data = [
            'Служба охраны общественного порядка гражданской защиты',
            'Медицинская служба гражданской защиты',
            'Служба газоснабжения гражданской защиты',
            'Служба спасения гражданской защиты',
            'Служба теплового хозяйства гражданской защиты',
            'Служба энергетики гражданской защиты',
            'Служба водоснабжения гражданской защиты',
            'Служба связи и обеспечения оповещения гражданской защиты',
            'Служба химической и радиационной защиты гражданский защиты',
            'Служба торговли гражданской защиты',
            'Транспортная служба гражданский защиты',
            'Служба ритуальных услуг гражданской защиты',
            'Служба защиты животных и растений гражданской защиты',
            'Служба защиты культурных  ценностей гражданской защиты',
            'Служба горюче – смазочных материалов гражданской защиты',
            'Инженерная служба АО «Инжстрой» и ГКП Алматытазалык',
            'Гидрометеорологическая служба РГП «Казгидромет»',
            'Департамент экологии',
            'Служба информации Акима г.Алматы',
            'АО «КАЗАВИСПАС КЧС МВД РК» г.Алматы',
            'РГКП «Казавиалесоохрана»',
            'РГУ «Иле-Алатауский» ГПП',
            'Департамент Обороны г.Алматы',
            'РГУ ВЧ №5571 Национальной гвардии РК',
            'Акимы районов',
        ];

        foreach ($data as $sort_order => $datum) {
            \App\CivilProtectionServiceBlock::firstOrCreate([
                'name' => $datum,
                'sort_order' => ++$sort_order,
            ]);
        }

        $category = \App\DictionaryCategory::name('Общий раздел')->first();

        $dictionary = \App\Dictionary::firstOrCreate([
            'table' => 'civil_protection_service_blocks',
            'title' => 'Службы гражданской защиты',
            'dictionary_category_id' => $category->id ?? null, // Общий раздел
            'sort_order' => 10,
            'model' => \App\CivilProtectionServiceBlock::class
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('civil_protection_service_blocks');
    }
}
