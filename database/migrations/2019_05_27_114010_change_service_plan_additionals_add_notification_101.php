<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeServicePlanAdditionalsAddNotification101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_plan_additionals', function (Blueprint $table) {
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
        Schema::table('service_plan_additionals', function (Blueprint $table) {
            $table->dropColumn(['notification_101','notification_112']);
        });
    }
}
