<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDictCityAreaAddDistrictManagersUsing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dict_city_area', function (Blueprint $table) {
            $table->boolean('district_managers_using')->nullable()->default(0)->after('id');
        });

        \App\Dictionary\CityArea::whereIn('id', [9, 10, 11])->update(['district_managers_using' => 0]);
        \App\Dictionary\CityArea::whereNotIn('id', [9, 10, 11])->update(['district_managers_using' => 1]);

        $cityAreaDict = \App\Dictionary::name('dict_city_area')->update(['url' => '/dictionaries/city-areas']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dict_city_area', function (Blueprint $table) {
            $table->dropColumn(['district_managers_using']);
        });

        $cityAreaDict = \App\Dictionary::name('dict_city_area')->update(['url' => null]);

    }
}
