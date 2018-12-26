<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDictTripResultAddShowDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dict_trip_result', function (Blueprint $table) {
            $table->boolean('show_in_daily_report101')->after('id')->nullable()->default(false);
        });

        $reasons = \App\Dictionary\TripResult::whereIn('name', [
            'Ложный',
            'АСР',
            'Кровельные, битумные, сварочные работы',
            'Бдительность населения',
            'Пища на газе и задымление при неиспр.быт.эл.приборов',
            'КЗ эл.сетей и эл.оборудования.',
            'Загорание бесхозных зданий, бесхозных транспортных средств',
            'Случаи пожаров трансп.средств в результате ДТП',
            'Прочие случаи загорания',
            'Область',
            'Технологический процесс',
            'Самовозгорание пирофорных соединений, без последствий и ущерба',
            'Загорание сухостоя, мусора на отк.территориях, в контейнерах',
            'Загорание мусора на тер.объекта,дома(лифт,мусоросб,л.клетка,подв,черд)',
            'Срабатывание сигнализации',
            'Пожары, в рез-те авиа, ж/д аварии, тер.актов и пр., землетрясения',
            'Покушение на самоубийство',
            'Вспышки и разряды стат.электричества'
        ])->get();

        foreach ($reasons as $reason) {
            $reason->show_in_daily_report101 = true;
            $reason->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dict_trip_result', function (Blueprint $table) {
            $table->dropColumn(['show_in_daily_report101']);
        });
    }
}
