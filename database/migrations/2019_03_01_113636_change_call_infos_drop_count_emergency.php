<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCallInfosDropCountEmergency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_infos', function (Blueprint $table) {
            $table->dropColumn(['count_emergency']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_infos', function (Blueprint $table) {
            $table->integer('count_emergency')->nullable()->after('id');
        });
    }
}
