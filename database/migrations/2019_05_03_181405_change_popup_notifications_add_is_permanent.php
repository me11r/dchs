<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePopupNotificationsAddIsPermanent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('popup_notifications', function (Blueprint $table) {
            $table->boolean('is_permanent')->after('is_viewed')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('popup_notifications', function (Blueprint $table) {
            $table->dropColumn(['is_permanent']);
        });
    }
}
