<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTicket101AddBuildingInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket101', function (Blueprint $table) {
            $table->unsignedInteger('wall_material_id')->nullable()->after('id');
            $table->foreign('wall_material_id')
                ->references('id')
                ->on('wall_materials')
                ->onDelete('cascade');

            $table->string('year_of_development')->nullable()->after('id');
            $table->string('building_square')->nullable()->after('id');
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
            $table->dropForeign(['wall_material_id']);
            $table->dropColumn(['wall_material_id', 'year_of_development', 'building_square']);
        });
    }
}
