<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDictionariesAddRecordEventInfoArrived extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dictionaries', function (Blueprint $table) {
            //
        });

        $dictionary = \App\Dictionary::name('dict_trunk')->update([
            'table' => 'event_info_arriveds',
            'url' => '/dictionaries/event-info-arrived',
            'model' => \App\EventInfoArrived::class
        ]);

        \App\Right::where('name', 'DICT_TRUNK')->delete();
        \App\Right::where('name', 'DICT_EVENT_INFO_ARRIVEDS')->update([
            'title' => 'Стволы'
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
            //
        });

        $dictionary = \App\Dictionary::name('event_info_arriveds')->update([
            'table' => 'dict_trunk',
            'url' => null,
            'model' => \App\Models\Trunk::class
        ]);

        \App\Right::create([
            'name' => 'DICT_TRUNK',
            'title' => 'Стволы',
            'right_group_id' => 8,
        ]);

        \App\Right::where('name', 'DICT_EVENT_INFO_ARRIVEDS')->update([
            'title' => 'Нормативно-справочная информация: на месте'
        ]);
    }
}
