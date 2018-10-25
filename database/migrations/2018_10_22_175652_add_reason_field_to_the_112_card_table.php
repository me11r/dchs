<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonFieldToThe112CardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->string('incident_place', 500)->nullable();
            $table->string('additional_incident_place', 500)->nullable();
            $table->text('reason')->nullable();
            $table->time('chronology_start_time')->nullable();
            $table->time('chronology_end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->dropColumn([
                'incident_place',
                'additional_incident_place',
                'reason',
                'chronology_start_time',
                'chronology_end_time'
            ]);
        });
    }
}
