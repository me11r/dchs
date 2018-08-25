<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101AddWaterSupplyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->unsignedInteger('water_supply_source_id')->nullable()->after('id');
            $table->foreign('water_supply_source_id')
                ->references('id')
                ->on('water_supply_sources')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->dropForeign('ticket101_water_supply_source_id_foreign');
            $table->dropColumn('water_supply_source_id');
        });
    }
}
