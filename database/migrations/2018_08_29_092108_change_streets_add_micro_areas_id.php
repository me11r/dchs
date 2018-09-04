<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStreetsAddMicroAreasId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('streets')->delete();

        Schema::table('streets', function (Blueprint $table) {
            $table->integer('city_area_id')->unsigned(true)->change();
        });

        Schema::table('streets', function (Blueprint $table) {
            $table->foreign('city_area_id')
                ->references('id')
                ->on('dict_city_area')
                ->onDelete('cascade');

            $table->unsignedInteger('city_micro_area_id')->after('city_area_id')->nullable();
            $table->foreign('city_micro_area_id')
                ->references('id')
                ->on('city_micro_areas')
                ->onDelete('cascade');

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('streets', function (Blueprint $table) {
            $table->dropForeign(['city_area_id']);
            $table->dropForeign(['city_micro_area_id']);
            $table->dropColumn(['city_micro_area_id']);
            $table->dropIndex('streets_name_index');
        });
    }
}
