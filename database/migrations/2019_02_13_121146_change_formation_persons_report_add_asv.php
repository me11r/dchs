<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationPersonsReportAddAsv extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_persons_report', function (Blueprint $table) {
            $table->integer('asv')->after('id')->nullable();
            $table->integer('dask')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_persons_report', function (Blueprint $table) {
            $table->dropColumn([
                'asv',
                'dask',
            ]);
        });
    }
}
