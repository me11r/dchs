<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAirRescueReportsSplitJetFuel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->dropColumn(['jet_fuel']);
            $table->integer('jet_fuel_action')->nullable()->after('id');
            $table->integer('jet_fuel_reserved')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('air_rescue_reports', function (Blueprint $table) {
            $table->dropColumn([
                'jet_fuel_action',
                'jet_fuel_reserved',
            ]);
            $table->integer('jet_fuel')->nullable()->after('id');
        });
    }
}
