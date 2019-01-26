<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard101AddEmergencyTypeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->unsignedInteger('emergency_type_id')->nullable()->after('id');
            $table->foreign('emergency_type_id')
                ->references('id')
                ->on('emergency_types')
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
            $table->dropForeign(['emergency_type_id']);
            $table->dropColumn(['emergency_type_id']);
        });
    }
}
