<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToFormationTech extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_tech_report', function (Blueprint $table) {
            $table->integer('asv')->nullable();
            $table->integer('dask')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_tech_report', function (Blueprint $table) {
            $table->dropColumn([
                'asv',
                'dask',
            ]);
        });
    }
}
