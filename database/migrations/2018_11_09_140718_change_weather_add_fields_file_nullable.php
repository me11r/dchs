<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeWeatherAddFieldsFileNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weather', function (Blueprint $table) {
            $table->string('number')->nullable()->after('id');
            $table->dropColumn('file');
            $table->longText('weather_now')->nullable()->after('id');
            $table->longText('water_now')->nullable()->after('id');
            $table->longText('radiation_now')->nullable()->after('id');
            $table->longText('atmosphere_now')->nullable()->after('id');
            $table->longText('address')->nullable()->after('id');
            $table->string('filial_director')->nullable()->after('id');
            $table->string('executor')->nullable()->after('id');

            $table->longText('forecast_area')->nullable()->after('id');

            $table->longText('forecast_city1')->nullable()->after('id');
            $table->longText('city1_abs_max')->nullable()->after('id');
            $table->longText('city1_abs_min')->nullable()->after('id');

            $table->longText('forecast_city2')->nullable()->after('id');
            $table->longText('city2_abs_max')->nullable()->after('id');
            $table->longText('city2_abs_min')->nullable()->after('id');

            $table->longText('forecast_water')->nullable()->after('id');
            $table->longText('forecast_atmosphere')->nullable()->after('id');


            $table->longText('note')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('weather', function (Blueprint $table) {
            $table->dropColumn([
                'number',
                'weather_now',
                'water_now',
                'radiation_now',
                'atmosphere_now',
                'address',
                'filial_director',
                'executor',
                'forecast_area',
                'forecast_city1',
                'city1_abs_max',
                'city1_abs_min',
                'forecast_city2',
                'city2_abs_max',
                'city2_abs_min',
                'forecast_water',
                'forecast_atmosphere',
                'note',
            ]);
            $table->text('file')->after('id');
        });
    }
}
