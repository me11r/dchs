<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112DeleteAdditionalIncidentPlace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->dropColumn(['additional_incident_place']);
            $table->unsignedInteger('additional_incident_place_id')->nullable()->after('id');
            $table->foreign('additional_incident_place_id')
                ->references('id')
                ->on('incident_places')
                ->onDelete('set null');
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
            $table->dropForeign(['additional_incident_place_id']);
            $table->dropColumn(['additional_incident_place_id']);
            $table->string('additional_incident_place', 500)->nullable()->after('id');
        });
    }
}
