<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFireDepartmentsAddFormationReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fire_departments', function (Blueprint $table) {
            $table->boolean('goes_in_formation_report')->after('id')->nullable();
        });

        \App\FireDepartment::whereNotIn('title',['ЦППС','ИПЛ','ОД'])
            ->update(
                ['goes_in_formation_report' => true]
            );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fire_departments', function (Blueprint $table) {
            $table->dropColumn(['goes_in_formation_report']);
        });
    }
}
