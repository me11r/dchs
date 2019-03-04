<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEmergencySituationsAddDateTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emergency_situations', function (Blueprint $table) {
            $table->timestamp('date_time')->after('id')->nullable();
            $table->dropColumn([
                'date',
                'time',
            ]);
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
            $table->dropColumn(['date_time']);
            $table->time('time')->after('id')->nullable();
            $table->date('date')->after('id')->nullable();
        });
    }
}
