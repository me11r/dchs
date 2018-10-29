<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAirRescueReportTechItemsAddWinchSurFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('air_rescue_report_tech_items', function (Blueprint $table) {
            $table->boolean('simplex')->default(0)->nullable()->after('id');
            $table->boolean('vsu3')->default(0)->nullable()->after('id');
            $table->boolean('vsu5')->default(0)->nullable()->after('id');
            $table->boolean('vsu10')->default(0)->nullable()->after('id');
            $table->boolean('winch')->default(0)->nullable()->after('id');
            $table->boolean('sur')->default(0)->nullable()->after('id');
            $table->boolean('external_suspension')->default(0)->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('air_rescue_report_tech_items', function (Blueprint $table) {
            $table->dropColumn([
                'simplex',
                'vsu3',
                'vsu5',
                'vsu10',
                'winch',
                'sur',
                'external_suspension',
            ]);
        });
    }
}
