<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDsptFieldToTheFiredeptChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_checks', function (Blueprint $table) {
            $table->boolean('is_dspt')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_department_checks', function (Blueprint $table) {
            $table->dropColumn(['is_dspt']);
        });
    }
}
