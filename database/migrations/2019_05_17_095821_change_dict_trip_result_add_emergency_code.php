<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDictTripResultAddEmergencyCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dict_trip_result', function (Blueprint $table) {
            $table->string('emergency_code')->after('id')->nullable();
        });

        \App\Dictionary\TripResult::where('name', 'Пожар')->update(['emergency_code' => '115']);
        \App\Dictionary\TripResult::whereNotIn('name', ['Письменное заявление о пожаре','Ложный','Пожар'])->update(['emergency_code' => '116']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dict_trip_result', function (Blueprint $table) {
            $table->dropColumn(['emergency_code']);
        });
    }
}
