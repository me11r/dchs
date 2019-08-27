<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddDepartureResultsSceneOfIncident extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function($table) {
        $table->text('departure_results_scene_of_incident')->nullable();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_112', function($table) {
        $table->dropColumn('departure_results_scene_of_incident');
        });
    }
}
