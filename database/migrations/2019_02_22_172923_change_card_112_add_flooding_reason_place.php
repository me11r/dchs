<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddFloodingReasonPlace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->unsignedInteger('flooding_place_id')->after('id')->nullable();
            $table->unsignedInteger('flooding_reason_id')->after('id')->nullable();
            $table->unsignedInteger('living_count')->after('id')->nullable();

            $table->foreign('flooding_place_id')
                ->references('id')
                ->on('flooding_places')
                ->onDelete('cascade');

            $table->foreign('flooding_reason_id')
                ->references('id')
                ->on('flooding_reasons')
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
        Schema::table('card_112', function (Blueprint $table) {
            $table->dropForeign(['flooding_place_id']);
            $table->dropForeign(['flooding_reason_id']);

            $table->dropColumn(['flooding_place_id']);
            $table->dropColumn(['flooding_reason_id']);
            $table->dropColumn(['living_count']);
        });
    }
}
