<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireChronology101sAddWaterDeliveryDistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chronology101s', function (Blueprint $table) {
            $table->unsignedInteger('water_delivery_distance')->nullable()->after('id');
        });

        Schema::table('chronology101_from_fds', function (Blueprint $table) {
            $table->unsignedInteger('water_delivery_distance')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chronology101s', function (Blueprint $table) {
            $table->dropColumn(['water_delivery_distance']);
        });

        Schema::table('chronology101_from_fds', function (Blueprint $table) {
            $table->unsignedInteger('water_delivery_distance')->nullable()->after('id');
        });
    }
}
