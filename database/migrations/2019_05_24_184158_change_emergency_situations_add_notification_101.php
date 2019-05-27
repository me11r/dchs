<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmergencySituationsAddNotification101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emergency_situations', function (Blueprint $table) {
            $table->boolean('notification_101')->nullable()->default(false)->after('id');
            $table->boolean('notification_112')->nullable()->default(false)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emergency_situations', function (Blueprint $table) {
            $table->dropColumn(['notification_101','notification_112']);
        });
    }
}
