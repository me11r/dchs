<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentResultsAddGetBack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->boolean('get_back')->default(false)->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_department_results', function (Blueprint $table) {
            $table->dropColumn('get_back');
        });
    }
}
