<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRoadtripPlanAddCard101OtherId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roadtrip_plan', function (Blueprint $table) {
            $table->unsignedInteger('card_id')->nullable(true)->change();
            $table->unsignedInteger('card101_other_id')->nullable();
            $table->foreign('card101_other_id')
                ->references('id')
                ->on('ticket101_others')
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
        Schema::table('roadtrip_plan', function (Blueprint $table) {
            $table->dropForeign(['card101_other_id']);
            $table->dropColumn(['card101_other_id']);
            $table->unsignedInteger('card_id')->nullable(false)->change();
        });
    }
}
