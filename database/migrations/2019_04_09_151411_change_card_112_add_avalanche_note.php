<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddAvalancheNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->text('avalanche_note')->after('avalanche_volume')->nullable();
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
            $table->dropColumn(['avalanche_note']);
        });
    }
}
