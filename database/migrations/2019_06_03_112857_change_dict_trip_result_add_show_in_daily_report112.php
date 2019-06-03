<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDictTripResultAddShowInDailyReport112 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dict_trip_result', function (Blueprint $table) {
            $table->boolean('show_in_daily_report112')->after('show_in_daily_report101')->default(true)->nullable();
        });

        $dict = \App\Dictionary::name('dict_trip_result')->update(['url' => '/dictionaries/trip-results']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dict_trip_result', function (Blueprint $table) {
            $table->dropColumn(['show_in_daily_report112']);
        });

        $dict = \App\Dictionary::name('dict_trip_result')->update(['url' => null]);
    }
}
