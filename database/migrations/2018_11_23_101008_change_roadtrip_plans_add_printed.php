<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRoadtripPlansAddPrinted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roadtrip_plan', function (Blueprint $table) {
            $table->boolean('printed')->nullable()->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roadtrip_plan', function (Blueprint $table) {
            $table->dropColumn(['printed']);
        });
    }
}
