<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101ServicePlansAddDispatchedTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_service_plans', function (Blueprint $table) {
            $table->timestamp('dispatched_time')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket101_service_plans', function (Blueprint $table) {
            $table->dropColumn('dispatched_time');
        });
    }
}
