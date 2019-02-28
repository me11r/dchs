<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddElevatorEmergencyTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->unsignedInteger('elevator_emergency_type_id')->nullable()->after('id');
            $table->foreign('elevator_emergency_type_id')
                ->references('id')
                ->on('elevator_emergency_types')
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
            $table->dropForeign(['elevator_emergency_type_id']);
            $table->dropColumn(['elevator_emergency_type_id']);
        });
    }
}
