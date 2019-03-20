<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFormationTechItemsAddDvr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formation_tech_items', function (Blueprint $table) {
            $table->boolean('dvr')->after('id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formation_tech_items', function (Blueprint $table) {
            $table->dropIndex(['dvr']);
            $table->dropColumn('dvr');
        });
    }
}
