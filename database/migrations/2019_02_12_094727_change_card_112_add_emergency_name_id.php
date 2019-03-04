<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddEmergencyNameId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->unsignedInteger('emergency_name_id')->after('id')->nullable();
            $table->foreign('emergency_name_id')
                ->references('id')
                ->on('emergency_names')
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
            $table->dropForeign(['emergency_name_id']);
            $table->dropColumn(['emergency_name_id']);
        });
    }
}
