<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFileToOperationalPlanAndCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_plans', function (Blueprint $table) {
            $table->string('file');
        });
        Schema::table('operational_cards', function (Blueprint $table) {
            $table->string('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_plans', function (Blueprint $table) {
            $table->dropColumn('file');
        });
        Schema::table('operational_cards', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }
}
