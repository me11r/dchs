<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYearOfDevelopmentToSpecialPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_plans', function (Blueprint $table) {
            $table->string('year_of_development')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_plans', function (Blueprint $table) {
            $table->dropColumn('year_of_development');
        });
    }
}
