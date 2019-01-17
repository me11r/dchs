<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationOdPersonItemsAddGsm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_od_person_items', function (Blueprint $table) {
            $table->string('gsm_count')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_od_person_items', function (Blueprint $table) {
            $table->dropColumn(['gsm_count']);
        });
    }
}
