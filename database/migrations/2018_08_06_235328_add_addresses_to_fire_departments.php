<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressesToFireDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_departments', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->integer('city_area_id')->nullable();

            $table->foreign('city_area_id')->name('aria_id')->references('id')->on('dict_city_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_departments', function (Blueprint $table) {
            $table->dropForeign('aria_id');
            $table->dropColumn('city_area_id');
            $table->dropColumn('address');
        });
    }
}
