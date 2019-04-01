<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeWeatherAddStormFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('weather', function (Blueprint $table) {
            $table->string('storm_warning_number')->after('id')->nullable();
            $table->date('storm_warning_date')->after('id')->nullable();
            $table->text('storm_warning_text')->after('id')->nullable();
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
                'storm_warning_number',
                'storm_warning_date',
                'storm_warning_text',
            ]);
        });
    }
}
