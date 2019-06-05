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

        $dictionary = \App\Dictionary::firstOrCreate([
            'table' => 'event_info_arriveds',
            'title' => 'События на месте (стволы)',
            'url' => '/dictionaries/event-info-arrived',
            'dictionary_category_id' => \App\DictionaryCategory::name('101')->first()->id ?? null,
            'sort_order' => 10,
            'model' => \App\EventInfoArrived::class
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

        $dictionary = \App\Dictionary::name('event_info_arriveds')->forceDelete();
    }
}
