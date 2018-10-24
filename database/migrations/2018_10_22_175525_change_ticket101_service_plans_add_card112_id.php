<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101ServicePlansAddCard112Id extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101_service_plans', function (Blueprint $table) {
            $table->unsignedInteger('card112_id')->nullable()->after('card_id');
            $table->foreign('card112_id')
                ->references('id')
                ->on('card_112')
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
        Schema::table('ticket101_service_plans', function (Blueprint $table) {
            $table->dropForeign(['card112_id']);
            $table->dropColumn(['card112_id']);
        });
    }
}
