<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDictTrunkAddTrunkTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_info_arriveds', function (Blueprint $table) {
            $table->unsignedInteger('trunk_type_id')->after('id')->nullable();
            $table->foreign('trunk_type_id')
                ->references('id')
                ->on('trunk_types')
                ->onDelete('cascade');
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_info_arriveds', function (Blueprint $table) {
            $table->dropForeign(['trunk_type_id']);
            $table->dropColumn(['trunk_type_id']);
            $table->dropSoftDeletes();
        });
    }
}
