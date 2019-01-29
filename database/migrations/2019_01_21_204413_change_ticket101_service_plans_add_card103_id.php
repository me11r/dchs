<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101ServicePlansAddCard103Id extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_service_plans', function (Blueprint $table) {
            $table->unsignedInteger('card103_id')->after('card112_id')->nullable();
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
            $table->dropColumn('card103_id');
        });
    }
}
