<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101HqRidesAddDistance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_hq_rides', function (Blueprint $table) {
            $table->integer('distance')->nullable()->after('id')->comment('Расстояние до места');
            $table->integer('staff_count')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101_hq_rides', function (Blueprint $table) {
            $table->dropColumn([
                'distance',
                'staff_count',
            ]);
        });
    }
}
