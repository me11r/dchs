<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddEmergencyTypeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->unsignedInteger('emergency_type_id')->nullable()->after('id');
            $table->foreign('emergency_type_id')
                ->references('id')
                ->on('emergency_types')
                ->onDelete('cascade');

            $table->longText('emergency_feature')->nullable()->after('id');

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
            $table->dropForeign(['emergency_type_id']);
            $table->dropColumn(['emergency_type_id','emergency_feature']);
        });
    }
}
