<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationTechItemsAddVehicleStatusId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_tech_items', function (Blueprint $table) {
            $table->unsignedInteger('vehicle_status_id')->after('id')->nullable();
            $table->foreign('vehicle_status_id')
                ->references('id')
                ->on('vehicle_statuses')
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
        Schema::table('formation_tech_items', function (Blueprint $table) {
            $table->dropForeign(['vehicle_status_id']);
            $table->dropColumn(['vehicle_status_id']);
        });
    }
}
