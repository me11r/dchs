<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCard112AddAvalanceVolume extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_112', function (Blueprint $table) {
            $table->integer('avalanche_volume')->nullable()->after('id');
            $table->unsignedInteger('avalanche_type_id')->nullable()->after('id');
            $table->foreign('avalanche_type_id')
                ->references('id')
                ->on('avalanche_types')
                ->onDelete('cascade');
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
            $table->dropColumn(['avalanche_volume']);
            $table->dropForeign(['avalanche_type_id']);
            $table->dropColumn(['avalanche_type_id']);
        });
    }
}
